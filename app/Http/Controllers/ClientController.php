<?php

namespace App\Http\Controllers;

use Facades\App\Helpers\Authentication;
use App\Http\Controllers\Controller;
use App\Models\LinkIndex;
use App\Models\User;
use App\Models\View;
use DB;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function login()
    {
        return view('login');
    }

    public function loginAction(Request $req)
    {
        $user = User::where('email', $req->input('email'))->first();
        if ($user !== null && $user->tryLogin($req->input('password'))) {
            Authentication::user($user->id);
            return redirect()->action('ClientController@dashboard')->with('success', 'Logged in successfully!');
        } else {
            $error = "Error - Email or password not recognised";
            return redirect()->back()->with('error', $error);
        }
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function indexer()
    {
        $user = Authentication::user();

        $processed_links = $user->linkIndexers()->where('progress', 2)->distinct('url')->count();
        $total_links = $user->linkIndexers()->distinct('url')->count();
        $total_links_in_queue = $user->linkIndexers()->where('progress', '<', 2)->distinct('url')->count();

        return view('indexer', compact('processed_links', 'total_links', 'total_links_in_queue'));
    }

    public function indexerAction(Request $req)
    {
        $links = explode(PHP_EOL, $req->input('links'));
        $linkIndexers = require(app_path() . '/Helpers/Indexers/Config.php');
        DB::transaction(function() use ($links, $linkIndexers) {
            foreach($links as $link) {
                $link = str_replace('\r', '', $link);
                
                foreach($linkIndexers as $linkIndexer) {
                    $queueItem = new LinkIndex();
                    $queueItem->url = $link;
                    $queueItem->class = $linkIndexer;
                    $queueItem->user_id = Authentication::user()->id;
                    $queueItem->save();
                }
            }
        });
        return redirect('indexer')->with('success', 'Successfully added ' . count($links) .' links to the indexer queue');
    }

    public function links($page = 1)
    {
        $pageRecordLimit = 50;

        $totalRecords = Authentication::user()->rootLinks()->count();
        $totalPages = ceil($totalRecords / $pageRecordLimit);

        $rootlinks = Authentication::user()
                        ->rootLinks()                        
                        ->skip(($page-1) * $pageRecordLimit)
                        ->take($pageRecordLimit)
                        ->withCount('views')
                        ->with('cache')
                        ->join('root_link_caches as cache', 'root_links.id', '=', 'cache.root_link_id')
                        ->orderBy('cache.rating', 'DESC')
                        ->get();

        $tableOutput = [];
        
        foreach($rootlinks as $rootlink) {
            $row = [];

            $row['id'] = $rootlink->id;
            $row['url'] = $rootlink->full_url;
            $row['views_count'] = $rootlink->views_count;
            $row['backlinks_count'] = $rootlink->backlinks_count;
            $row['views_per_day'] = $rootlink->views_per_day;
            $row['crawled_date'] = $rootlink->crawled_date;
            $row['indexed_date'] = $rootlink->indexed_date;
            $row['rating'] = $rootlink->rating;

            $tableOutput[] = (object) $row;
        }

        $tableOutput = collect($tableOutput);

        return view('links.links',
                    compact(
                        'tableOutput',
                        'page',
                        'totalPages',
                        'totalRecords'
                    )
                );
    }
}