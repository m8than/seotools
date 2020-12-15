<?php

namespace App\Console\Commands;

use App\Models\LinkIndex;
use Exception;
use Illuminate\Console\Command;

class RunIndexers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexer:process {limit=700}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes Index queue';

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
        $linkIndexes = LinkIndex::where('progress', '=', 0)->limit($this->argument('limit'))->get();
        LinkIndex::where('progress', '=', 0)
                 ->whereIn('id', $linkIndexes->pluck('id'))
                 ->update(['progress' => 1]);
        
        foreach ($linkIndexes as $linkIndex) {
            try {
                $instance = app($linkIndex->class);
                $success = $instance->index($linkIndex->url);
                $linkIndex->success = $success;
                $linkIndex->progress = 2;
            } catch(Exception $e) {
                $linkIndex->success = false;
                $linkIndex->progress = 2;
            }
            $linkIndex->save();
        }
    }
}
