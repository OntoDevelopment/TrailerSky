<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;

class LogsController extends AdminController
{
    public function notifications()
    {
        // read log from storage/logs/yt_notifications.log
        $log = Storage::disk('logs')->get('yt_notifications.log');
        return view('admin.logs.notifications', compact('log'));
    }

    protected function getNotifications()
    {
        $files = Storage::disk('notifications')->files();

        return array_map(function ($file) {
            $data = json_decode(Storage::disk('notifications')->get($file));
            $data->file = $file;
            return $data;
        }, $files);
    }
}