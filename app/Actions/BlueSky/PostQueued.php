<?php

namespace App\Actions\BlueSky;

use App\Actions\AbstractAction;

use App\Http\Clients\BlueSky;

use App\Models\Post as PostModel;

class PostQueued extends AbstractAction
{
    public function run($params = [])
    {
        $this->log('Running BlueSky\PostQueued');

        // where platform_id is null and created_at in future
        $Post = PostModel::where('platform', 'bluesky')
            ->whereNull('platform_id')
            ->where('created_at', '>', now())
            ->first();
            
        if (!$Post) {
            $this->log('No queued posts found', true);
            return;
        }

        try {
            $response = BlueSky::post($Post);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), true);
            return;
        }
        $this->log('Posted to BlueSky');

        $Post->platform_id = $response->getCid();
        $Post->save();
    }
}
