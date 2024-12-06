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
        $this->log('Running BlueSky\PostQueued');
 
        // where platform_id is null and created_at in future
        $Post = PostModel::whereNull('platform_id')->where('created_at', '>', now())->first();
        if (!$Post) {
            $this->log('No queued posts found', true);
            return;
        }

        $Video = Video::find($Post->video_id);
        try {
            $response = BlueSky::post($Video);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), true);
            return;
        }
        $this->log('Posted to BlueSky');

        $Post->platform_id = $response->getCid();
        $Post->save();
    }
}
