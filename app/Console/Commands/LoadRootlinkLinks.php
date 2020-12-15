<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Models\RootLink;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class LoadRootlinkLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rootlinks:links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get links found on rootlinks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $updateInterval = 3600 * 24;
        // where doesn't have links or links out of date
        $toUpdate = RootLink::doesntHave('links')
                            ->orWhereHas('links', function(Builder $query) use ($updateInterval) {
                                $query->where('updated_at', '<', Carbon::now()->subSeconds($updateInterval)->toDateTimeString());
                            })->get();

                            
        foreach ($toUpdate as $rootlink) {
            /** @var RootLink $rootlink */
            Link::reloadLinks($rootlink);
        }
    }
}
