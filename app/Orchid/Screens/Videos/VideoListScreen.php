<?php
namespace App\Orchid\Screens\Videos;

use App\Orchid\Screens\AppScreen;

use App\Models\Video;

class VideoListScreen extends AppScreen
{
    public $name = 'Videos';

    public function query(): array
    {
        $query = Video::filters()->defaultSort('created_at', 'desc');
        return [
            'videos' => $query->paginate()
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
}

