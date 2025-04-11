<?php

namespace App\Console\Commands;

use App\Actions\YouTube\Search;

class GetVideos extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube:search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search YouTube for videos and import them';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $Action = Search::exec();
        foreach ($Action->log as $log) {
            if ($log->error) {
                $this->error($log->message);
            } else {
                $this->info($log->message);
            }
        }
    }
}
