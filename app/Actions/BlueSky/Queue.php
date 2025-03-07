<?php

namespace App\Actions\BlueSky;

use App\Actions\AbstractAction;

use App\Models\Video;
use App\Models\Post;

class Queue extends AbstractAction
{
    public function run($params = [])
    {
        $this->log('Running BlueSky\Queue');

        $Video = Video::toPost()
            ->joinMedia()
            ->orderBy('media.tmdb_popularity', 'desc')
            ->first();
        if (!$Video) {
            $this->log('No videos to queue');
            return;
        }

        $Post = new Post();
        $Post->video_id = $Video->id;
        $Post->platform = 'bluesky';
        $Post->content = $Video->postGenerate(300);
        $Post->save();
        $this->log('Post queued');
    }
}
