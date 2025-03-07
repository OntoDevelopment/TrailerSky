<?php

namespace App\Actions;

use App\Actions\AbstractAction;

use App\Http\Clients\TMDB;

use App\Models\Video;
use App\Models\Media;

class SetTMDB extends AbstractAction
{
    public function run($params = [])
    {
        $this->log('Running set TMDB ID');
        if (!isset($params['id'])) {
            $this->log('No ID provided', true);
            return;
        }
        $id = $params['id'];

        if (!isset($params['tmdb_id'])) {
            $this->log('No TMDB ID provided', true);
            return;
        }
        $tmdb_id = $params['tmdb_id'];

        $Video = Video::find($params['id']);

        if (!$Video) {
            $this->log('Video not found', true);
            return;
        }

        $Media = Media::where('tmdb_id', $tmdb_id)->first();
        if (!$Media) {
            $details = false;
            try {
                $details = TMDB::details($tmdb_id, 'tv');
                $media_type = 'tv';
            } catch (\Exception $e) {
                $this->log($e->getMessage(), true);
            }

            if (!$details) {
                try {
                    $details = TMDB::details($tmdb_id, 'movie');
                    $media_type = 'movie';
                } catch (\Exception $e) {
                    $this->log($e->getMessage(), true);
                }
            }
            if (!$details) {
                $this->log('No details found', true);
                return;
            }

            $Media = Media::make([
                'title' => $media_type == 'movie' ? $details['title'] : $details['name'],
                'tmdb_id' => $tmdb_id,
                'media_type' => $media_type,
                'tmdb_popularity' => round($details['popularity'], 3),
                'imdb_id' => $details['imdb_id'] ?? null,
                'release_date' => TMDB::bestAirdate($details),
            ]);
            $Media->save();
        }

        $Video->media_id = $Media->id;

        $Video->save();

        $this->log('Set media_id ' . $Video->title);
    }
}
