<?php

namespace App\Actions\YouTube;

use App\Entities\YouTubeVideo;
use App\Http\Clients\YouTube;
use App\Models\Video;
use Illuminate\Support\Carbon;

class ImportVideo extends \App\Actions\AbstractAction
{
    use \App\Actions\Traits\FetchesDetails;

    public function run($params = [])
    {
        // do what UtilController::videos does
        if (! isset($params['id'])) {
            return $this->log('No video ID provided');
        }
        $Video = Video::find($params['id']);
        if ($Video) {
            return $this->log('Video already imported');
        }

        $response = YouTube::video($params['id']);
        if (empty($response['items'][0]['id'])) {
            return $this->log('Video not found', true);
        }

        $YouTubeVideo = new YouTubeVideo($response['items'][0]);
        $Video = $YouTubeVideo->make();

        $this->log("<b>{$Video->title}</b> | {$Video->channel_name}");
        $this->fetchDetails($Video);

        $this->log('Done at ' . Carbon::now());
    }
}
