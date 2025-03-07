<?php

namespace App\Console\Commands;

use App\Actions\BlueSky;

class QueueBluesky  extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:bluesky';

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
        $Action = BlueSky\Queue::exec();
        foreach($Action->log as $log){
            if($log->error){
                $this->error($log->message);
            } else {
                $this->info($log->message);
            }
        }
    }
}
