<?php

namespace App\Actions\BlueSky;

use App\Actions\AbstractAction;
use App\Http\Clients\BlueSky;
use App\Models\Post as PostModel;
use App\Models\Video;

class Post extends AbstractAction
{
    public function run($params = [])
    {
        $this->log('Running BlueSky\Post');
        if (! isset($params['id'])) {
            $this->log('No ID provided', true);

            return;
        }
        $Video = Video::find($params['id']);
        if (! $Video) {
            $this->log('Video not found', true);

            return;
        }

        $Post = new PostModel;
        $Post->video_id = $Video->id;
        $Post->platform = 'bluesky';
        $Post->content = $Video->postGenerate(300);
        $Post->save();

        try {
            $response = BlueSky::post($Post);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), true);

            return;
        }
        $this->log('Posted to BlueSky');

        $Post->platform_id = $response->getCid();
        $Post->save();
        $this->log('Post recorded');
    }
}
