<?php

namespace App\Actions;

abstract class AbstractAction
{
    public $log = [];

    abstract public function run($params = []);

    public static function exec($params = [])
    {
        $Action = new static;
        $Action->run($params);
        return $Action;
    }

    public function log($message, $error = false)
    {
        $this->log[] = (object) [
            'message' => $message,
            'error' => $error
        ];
    }

    public function hasError()
    {
        foreach ($this->log as $log) {
            if ($log['error']) {
                return true;
            }
        }
        return false;
    }
}