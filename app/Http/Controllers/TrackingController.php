<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Link;
use App\Models\RootLink;
use App\Models\RootLinkCache;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TrackingController extends Controller
{
    /**
     * Generates tracking image, tracks referer and loads backlinks
     */
    public function imageTrack($category, $user, Request $req)
    {
        if ($req->headers->has('referer')) {
            $referer = $req->headers->get('referer');
            $rootLink = RootLink::addUrlOrCreate($referer, $user, $category);
            View::addViewOrCreate($rootLink, $req);
        }
        
        $response = Response::make(hex2bin('89504e470d0a1a0a0000000d494844520000000100000001010300000025db56ca00000003504c5445000000a77a3dda0000000174524e530040e6d8660000000a4944415408d76360000000020001e221bc330000000049454e44ae426082'));
        $response->header('Content-Type', 'image/png');
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');

        return $response;
    }

    public function imageTrackByCategory($categorySlug, Request $req)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        return $this->imageTrack($category->id, $category->user_id, $req);
    }
}