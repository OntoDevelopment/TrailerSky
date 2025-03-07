<?php

namespace App\Actions\YouTube;

use App\Models\Video;

use \Illuminate\Support\Carbon;

class FetchDetails extends \App\Actions\AbstractAction
{

    use \App\Actions\Traits\FetchesDetails;

    public function run($params = [])
    {
        // do what UtilController::videos does
        if (isset($params['id'])) {
            $Video = Video::find($params['id']);
            $this->log("<b>{$Video->title}</b> | {$Video->channel_name}");
            $this->fetchDetails($Video);
        } else {
            $this->log("Fetching all videos");
            $videos = Video::whereNull('media_id')->whereIn('type', ['trailer', 'teaser'])->whereNull('dismissed_at')->get();
            foreach ($videos as $Video) {
                $this->log("<b>{$Video->title}</b> | {$Video->channel_name}");
                $this->fetchDetails($Video);
            }
        }
        $this->log("Done at " . Carbon::now());
    }

    
}
