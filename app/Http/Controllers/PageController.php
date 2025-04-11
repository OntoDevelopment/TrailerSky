<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    public function page($view)
    {
        if (view()->exists('page.' . $view)) {
            return view('page.' . $view);
        } else {
            abort(404);
        }
    }

    public function about()
    {
        $channel_query = Channel::orderBy('wait_hours')->orderBy('name');
        $channels = Cache::remember(hash_query($channel_query), 60 * 60, fn () => $channel_query->get());

        return view('page.about', compact('channels'));
    }
}
