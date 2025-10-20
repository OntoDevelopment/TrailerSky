<?php

namespace App\Actions\Traits;

use App\Http\Clients\TMDB;
use App\Http\YouTube\Channels;
use App\Models\Hashtag;
use App\Models\Media;
use App\Models\Video;

trait FetchesDetails
{
    /**
     * Fetch details from TMDB and update the video
     *
     * @return Video
     */
    protected function fetchDetails(Video $Video)
    {
        $Channel = Channels::byId($Video->channel_id);
        $media_title = $Channel->videoMediaTitle($Video->title);
        $this->log("Media title: \"{$media_title}\"");
        $result = TMDB::find($media_title, $Video);

        if (! $result) {
            $this->log("No results found on TMDB. Type: {$Video->guessType('multi')}");

            return;
        } else {
            $this->log("TMDB: {$result['id']}");
        }
        if ($Video->type == 'undefined') {
            $Video->type = $Channel->videoType($Video->title)->enum();
        }
        $Media = $this->findMedia($result);
        $Media = $this->syncTMDB($Media);
        $Video->media_id = $Media->id;
        $Video->save();

        return $Video;
    }

    protected function syncTMDB($Media)
    {
        $details = TMDB::details($Media->tmdb_id, $Media->media_type);
        $Media->tmdb_popularity = round($details['popularity'], 3);
        if (! empty($details['imdb_id'])) {
            $Media->imdb_id = $details['imdb_id'];
        }
        if (TMDB::bestAirdate($details)) {
            $Media->release_date = TMDB::bestAirdate($details);
        }
        $Media->tmdb_poster_path = $details['poster_path'] ?? null;
        $Media->original_language = $details['original_language'] ?? null; // iso_639_1

        // build hashtags
        $hashtags = [];
        foreach ($details['genres'] as $genre) {
            $hashtags[] = Hashtag::lookup($genre['name'], 10);
        }
        foreach ($details['credits']['cast'] as $cast) {
            $hashtags[] = Hashtag::lookup($cast['name'], $cast['popularity'] ?? 0);
        }
        foreach ($details['production_companies'] as $company) {
            $hashtags[] = Hashtag::lookup($company['name'], 1);
        }
        // attach hashtags to media
        $Media->hashtags()->sync($hashtags);

        $Media->save();

        return $Media;
    }

    protected function findMedia($result)
    {
        $Media = Media::where('tmdb_id', $result['id'])->first();
        if (! $Media) {
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
