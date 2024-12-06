<?php

namespace App\Actions\YouTube;

use App\Entities\YouTubeVideo;

use App\Models\Channel;
use App\Models\Video;

use App\Http\YouTube\Channels;

use \Illuminate\Support\Carbon;

class ScrapeChannels extends \App\Actions\AbstractAction
{

    use Traits\FetchesDetails;

    public function run($params = [])
    {
        if (isset($params['id'])) {
            $this->scrapeChannel($params['id']);
        } else {
            $this->log("Scraping all channels");
            foreach (Channels::$list as $class) {
                $this->scrapeChannel($class::$id);
            }
        }
        $this->log("Done at " . Carbon::now());
    }

    protected function scrapeChannel($id)
    {
        $class = Channels::byId($id);
        $channel = new $class();
        $ChannelModel = Channel::find($class::$id);
        if (!$ChannelModel) {
            $ChannelModel = new Channel();
            $ChannelModel->id = $class::$id;
            $ChannelModel->name = $channel::$name;
            $ChannelModel->last_query = Carbon::now()->subDays(7);
            $ChannelModel->save();
        }
        // check if enough time has passed since last query
        if ($ChannelModel->last_query->diffInHours() < $ChannelModel->wait_hours) {
            return;
        }
        $this->log("<b>{$channel::$name}</b>");
        $this->log("Last query: " . $ChannelModel->last_query);


        try {
            // get videos since last query
            $results = $channel->getRecent($ChannelModel->last_query->format('c'));
        } catch (\Exception $e) {
            $this->log("Error: " . $e->getMessage(), true);
            return;
        }

        $this->log("Results: " . count($results));
        foreach ($results as $result) {
            $YouTubeVideo = new YouTubeVideo($result);
            $Video = Video::find($YouTubeVideo->id());
            if (!$Video) {
                $this->log(">   " . $YouTubeVideo->title());
                $Video = $YouTubeVideo->make();
            }
            if (in_array($Video->type, ['trailer', 'teaser'])) {
                $this->fetchDetails($Video);
            }
        }

        // update last query time
        $ChannelModel->last_query = Carbon::now();
        $ChannelModel->save();
    }
}
