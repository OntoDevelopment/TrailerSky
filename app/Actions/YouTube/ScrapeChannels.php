<?php

namespace App\Actions\YouTube;

use App\Entities\YouTubeVideo;
use App\Http\YouTube\Channels;
use App\Models\Channel;
use App\Models\Video;
use Illuminate\Support\Carbon;

class ScrapeChannels extends \App\Actions\AbstractAction
{
    use \App\Actions\Traits\FetchesDetails;

    public function run($params = [])
    {
        if (isset($params['id'])) {
            $this->scrapeChannel($params['id'], true);
        } else {
            $this->log('Scraping all channels');
            foreach (Channels::$list as $class) {
                $this->scrapeChannel($class::$id);
            }
        }
        $this->log('Done at ' . Carbon::now());
    }

    protected function scrapeChannel($id, $force = false)
    {
        $channel = Channels::byId($id);

        $ChannelModel = Channel::find($channel::$id);
        if (! $ChannelModel) {
            $ChannelModel = new Channel;
            $ChannelModel->id = $channel::$id;
            $ChannelModel->name = $channel->name();
            $ChannelModel->last_query = Carbon::now()->subDays(7);
            $ChannelModel->save();
        }
        // check if enough time has passed since last query
        if (! $force && $ChannelModel->last_query->diffInHours() < $ChannelModel->wait_hours) {
            return;
        }
        $this->log("<b>{$channel->name()}</b>");
        $this->log('Last query: ' . $ChannelModel->last_query);

        try {
            // get videos since last query
            $results = $channel->getRecent($ChannelModel->last_query->subHour(1)->format(ZULU));
        } catch (\Exception $e) {
            $this->log('Error: ' . $e->getMessage(), true);

            return;
        }

        $this->log('Results: ' . count($results));
        $last_query = Carbon::now();
        foreach ($results as $result) {
            $YouTubeVideo = new YouTubeVideo($result);
            $Video = Video::find($YouTubeVideo->id());
            if (! $Video) {
                $this->log('>   ' . $YouTubeVideo->title());
                $Video = $YouTubeVideo->make();
            }
            if (in_array($Video->type, ['trailer', 'teaser'])) {
                $this->fetchDetails($Video);
            }
            $last_query = $YouTubeVideo->created_at();
        }

        // update last query time
        $ChannelModel->last_query = $last_query;
        $ChannelModel->save();
    }
}
