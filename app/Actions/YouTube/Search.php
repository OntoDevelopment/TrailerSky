<?php

namespace App\Actions\YouTube;

use App\Entities\YouTubeVideo;
use App\Http\Clients\YouTube;
use App\Http\YouTube\Channels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Search extends \App\Actions\AbstractAction
{
    use \App\Actions\Traits\FetchesDetails, Traits\ImportsVideos;

    public function run($params = [])
    {
        $publishedAfter = Cache::rememberForever('youTubeSearchDate', function () {
            return Carbon::now()->subDays(1)->format(ZULU);
        });
        $publishedAfter = Carbon::now()->subHours(3)->format(ZULU);
        $this->log('Searching for new videos w/ "Trailer" since ' . $publishedAfter);

        $params = [
            'q' => 'Trailer',
            'part' => 'snippet,id',
            'type' => 'video',
            'maxResults' => 50,
            'publishedAfter' => $publishedAfter,
            'relevanceLanguage' => 'en',
            'regionCode' => 'US',
        ];

        try {
            $response = YouTube::search($params);
        } catch (\Exception $e) {
            $this->log('Error: ' . $e->getMessage(), true);

            return;
        }
        exit('<pre>' . print_r($response, true) . '</pre>');
        Cache::store('file')->put('youTubeSearchDate', Carbon::now()->format(ZULU), 1440);

        // map $response['items'] to YouTubeVideo objects
        $results = array_map(function ($result) {
            return new YouTubeVideo($result);
        }, $response['items']);

        //filter $results to only include videos from the channels we care about
        $channelIds = Channels::ids();
        $results = array_filter($results, function (YouTubeVideo $result) use ($channelIds) {
            return in_array($result->channelId(), $channelIds);
        });

        $imported = $this->importVideos($results);
        $this->log('Imported <b>' . count($imported) . '</b> videos at ' . Carbon::now());
    }
}
