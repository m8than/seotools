<?php

namespace App\Console\Commands;

use App\Models\RootLinkCache;
use Illuminate\Console\Command;

class RootLinksCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rootlinks:cache {interval=120}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches expensive link calculations';

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
        RootLinkCache::generateCache($this->argument('interval'));
    }
}
