<?php

namespace App\Http\Controllers\Admin;

use App\Models\Video;

class DashboardController extends AdminController
{
    public function index()
    {
        $vars = [
            'to_post' => Video::toPost()->get(),
            'to_review' => Video::toReview()->get(),
        ];
        
        return view('admin.dashboard', $vars);
    }

    public function runplaceholder(){
        return view('admin.runplaceholder');
    }
}
