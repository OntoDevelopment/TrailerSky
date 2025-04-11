<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use App\Models\Media;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function hashtag(Request $request, $hashtag)
    {
        $hashtag = Hashtag::where('text', $hashtag)->firstOrFail();
        $media_ids = DB::table('media_hashtags')->where('hashtag_id', $hashtag->id)->pluck('media_id');

        $query = Video::whereIn('media_id', $media_ids)->orderBy('created_at', 'desc');
        $videos = Cache::remember(hash_query($query), 5 * 60, fn () => $query->get());

        return view('hashtag', compact('videos', 'hashtag'));
    }

    public function movies()
    {
        $query = $this->queryMedia('movie');
        $media_list = Cache::remember(hash_query($query), 5 * 60, fn () => $query->get());

        return view('movies', compact('media_list'));
    }

    public function shows()
    {
        $query = $this->queryMedia('tv');
        $media_list = Cache::remember(hash_query($query), 5 * 60, fn () => $query->get());

        return view('shows', compact('media_list'));
    }

    private function queryMedia($type)
    {
        return Media::where('media_type', $type)->orderBy('title', 'asc');

    }
}
