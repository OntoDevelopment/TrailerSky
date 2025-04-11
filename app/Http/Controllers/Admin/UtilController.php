<?php

namespace App\Http\Controllers\Admin;

use App\Actions;
use App\Http\Clients\TMDB;
use App\Models\Media;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UtilController extends AdminController
{
    public function dismiss(Request $request)
    {
        $Video = Video::find($request->id);
        $Video->dismissed_at = Carbon::now();
        $Video->save();
        echo 'Dismissed ' . $Video->title;
    }

    public function set_tmdb_id(Request $request)
    {
        $Video = Video::find($request->id);

        $Media = Media::where('tmdb_id', $request->tmdb_id)->first();
        if (! $Media) {
            $details = false;
            try {
                $details = TMDB::details($request->tmdb_id, 'tv');
                $media_type = 'tv';
            } catch (\Exception $e) {
            }

            if (! $details) {
                try {
                    $details = TMDB::details($request->tmdb_id, 'movie');
                    $media_type = 'movie';
                } catch (\Exception $e) {
                }
            }
            if (! $details) {
                echo 'No details found';

                return;
            }

            $Media = Media::make([
                'title' => $media_type == 'movie' ? $details['title'] : $details['name'],
                'tmdb_id' => $request->tmdb_id,
                'media_type' => $media_type,
                'tmdb_popularity' => round($details['popularity'], 3),
                'imdb_id' => $details['imdb_id'] ?? null,
                'release_date' => TMDB::bestAirdate($details),
            ]);
            $Media->save();
        }

        $Video->media_id = $Media->id;
        $Video->save();
        echo 'Set media_id ' . $Video->title;
    }

    public function action(Request $request, $action)
    {
        $actionClass = $this->actionClass($action);
        if (! $actionClass) {
            return 'Action not found';
        }
        $Action = new $actionClass;
        $Action->run($request->query());

        return view('admin.util.action', compact('Action'));
    }

    /**
     * @param  string  $action
     * @return \App\Actions\AbstractAction|false
     */
    protected function actionClass($action)
    {
        switch ($action) {
            case 'postBlueSky':
                return Actions\BlueSky\Post::class;
            case 'scrapeYouTubeChannels':
                return Actions\YouTube\ScrapeChannels::class;
            case 'searchYouTube':
                return Actions\YouTube\Search::class;
            case 'fetchYouTubeDetails':
                return Actions\YouTube\FetchDetails::class;
            case 'importYouTube':
                return Actions\YouTube\ImportVideo::class;
            case 'updateMedia':
                return Actions\TMDB\UpdateMedia::class;
            case 'subscribe':
                return Actions\PubSubHubBub\Subscribe::class;
            default:
                return false;
        }
    }
}
