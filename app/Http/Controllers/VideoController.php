<?php
namespace App\Http\Controllers;

use App\Models\Video;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request, $id)
    {
        $video = Video::findOrfail($id);
        $title = $video->title;
        switch($video->type){
            case 'trailer':
                $title .= 'Official Trailer';
                break;
            case 'teaser':
                $title .= 'Official Teaser Trailer';
                break;
        }
        $title .= ' #TrailerSky';
        return view('video', compact('video', 'title'));
    }
}