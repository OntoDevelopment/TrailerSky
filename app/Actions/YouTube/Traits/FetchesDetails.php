<?php

namespace App\Actions\YouTube\Traits;

use App\Http\Clients\TMDB;
use App\Models\Video;
use App\Models\Media;
use App\Models\Hashtag;

use App\Http\YouTube\Channels;

trait FetchesDetails {
    protected function fetchDetails(Video $Video)
    {
        $Channel = new (Channels::byId($Video->channel_id));
        $media_title = $Channel->videoMediaTitle($Video->title);
        $this->log("Media title: \"{$media_title}\"");
        $result = TMDB::find($media_title, $Video);

        if (!$result) {
            $this->log("No results found on TMDB");
            return;
        } else {
            $this->log("TMDB: {$result['id']}");
        }
        if ($Video->type == 'undefined') {
            $Video->type = $Channel->videoType($Video->title)->enum();
        }
        $Media = $this->findMedia($result);

        $details = TMDB::details($result['id'], $result['media_type']);

        $Media->tmdb_popularity = round($result['popularity'], 3);
        $Media->imdb_id = $details['imdb_id'] ?? null;
        $release_date = $result['media_type'] == 'movie' ? $details['release_date'] : $details['first_air_date'];
        if($release_date) {
            $Media->release_date = $release_date;
        }

        // build hashtags
        $hashtags = [];
        foreach ($details['genres'] as $genre) {
            $hashtags[] = Hashtag::lookup($genre['name'], 10);
        }
        foreach ($details['production_companies'] as $company) {
            $hashtags[] = Hashtag::lookup($company['name'], 1);
        }
        // attach hashtags to media
        $Media->hashtags()->sync($hashtags);

        $Video->media_id = $Media->id;
        $Video->save();

        $Media->save();
    }

    private function findMedia($result)
    {
        $Media = Media::where('tmdb_id', $result['id'])->first();
        if (!$Media) {
            $title = $result['media_type'] == 'movie' ? $result['title'] : $result['name'];
            $Media = new Media([
                'title' => $title,
                'tmdb_id' => $result['id'],
                'media_type' => $result['media_type'],
            ]);
        }

        $Media->save();
        return $Media;
    }
}