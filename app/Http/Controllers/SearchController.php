<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::whereNotNull('media_id');
        $videos = Cache::remember(hash_query($query), 5 * 60, fn () => $query->get());

        return view('search', compact('videos'));
    }
}
