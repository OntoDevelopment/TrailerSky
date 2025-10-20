<?php

namespace App\Orchid\Screens\Videos;

use App\Models\Video;
use App\Orchid\Screens\AppScreen;
use Illuminate\Support\Facades\Request;

class VideoListScreen extends AppScreen
{
    public $name = 'Videos';

    public function query(): array
    {
        $query = Video::filters()->defaultSort('created_at', 'desc');

        return [
            'videos' => $query->paginate(),
        ];
    }

    public function commandBar(): array
    {
        return [];
    }

    public function layout(): array
    {
        $layout = [
            \App\Orchid\Layouts\Videos\VideoFiltersLayout::class,
            \App\Orchid\Layouts\Videos\VideoListLayout::class,

        ];

        return $layout;
    }

    public function import_video(Request $request)
    {
        $youtube = $request->input('youtube_url');
        $url_parts = parse_url($youtube);
        parse_str($url_parts['query'], $query_params);
        if (! $query_params['v']) {
            return redirect()->back()->with('error', 'Invalid YouTube URL');
        }
        $action = new \App\Actions\YouTube\ImportVideo;
        $action->run(['id' => $query_params['v']]);
        if ($action->hasError()) {
            return redirect()->back()->with('error', 'Error importing video');
        }

        return redirect()->back()->with('success', 'Video imported successfully');
    }
}
