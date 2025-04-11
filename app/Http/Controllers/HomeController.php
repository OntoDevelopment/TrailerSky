<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $hot_query = Video::hot();
        $hot = Cache::remember(hash_query($hot_query), 5 * 60, fn () => $hot_query->get());

        return view('home', compact('hot'));
    }
}
