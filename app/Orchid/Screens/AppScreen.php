<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

abstract class AppScreen extends Screen
{
    public function runAction(string $action_class, $params = [])
    {
        $action = new $action_class;
        $action->run($params);

        foreach ($action->log as $log) {
            if ($log->error) {
                Toast::error($log->message);
            } else {
                Toast::info($log->message);
            }
        }
    }
}
