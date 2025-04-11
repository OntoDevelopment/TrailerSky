<?php

namespace App\Console\Commands;

use App\Actions\PubSubHubBub;

class Subscribe extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to YouTube channels via PubSubHubBub';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $Action = PubSubHubBub\Subscribe::exec();
        foreach ($Action->log as $log) {
            if ($log->error) {
                $this->error($log->message);
            } else {
                $this->info($log->message);
            }
        }
    }
}
