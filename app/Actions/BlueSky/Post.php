<?php

namespace App\Actions\BlueSky;

use App\Actions\AbstractAction;

use App\Http\Clients\BlueSky;
use App\Models\Video;
use App\Models\Post as PostModel;

class Post extends AbstractAction
{
    public function run($params = [])
    {
        $this->log('Running BlueSky\Post');
        if (!isset($params['id'])) {
            $this->log('No ID provided', true);
            return;
        }
        $Video = Video::find($params['id']);
        if (!$Video) {
            $this->log('Video not found', true);
            return;
        }
        try {
            $response = BlueSky::post($Video);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), true);
            return;
        }
        $this->log('Posted to BlueSky');
        $Post = new PostModel();
        $Post->video_id = $Video->id;
        $Post->platform = 'bluesky';
        $Post->platform_id = $response->getCid();
        $Post->save();
        $this->log('Post recorded');
    }
}
