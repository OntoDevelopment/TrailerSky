<?php

namespace App\Http\Controllers;

use App\Entities\YouTubeVideo;
use App\Http\Clients\YouTube;
use App\Http\YouTube\Channels;
use App\Models\Video;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    use \App\Actions\Traits\FetchesDetails;

    public function verify(Request $request)
    {
        return response($request->input('hub_challenge'), 200, ['Content-Type' => 'text/plain']);
    }

    public function notify(Request $request)
    {
        $this->logRequest($request);
        try {
            $this->processAtom($request);
        } catch (\Exception $e) {
            $this->log($e->getMessage());
        }
        $this->log('---');
        if ($request->has('hub_challenge')) {
            return response($request->input('hub_challenge'), 200, ['Content-Type' => 'text/plain']);
        }
    }

    protected function processAtom(Request $request)
    {
        $this->log('Received notification from YouTube ' . date('Y-m-d_H-i-s'));

        $Notification = simplexml_load_string($request->getContent());

        if (! $Notification) {
            return $this->log('Invalid notification xml');
        }

        $videoId = $Notification->entry->children('yt', true)->videoId->__toString() ?? null;
        $channelId = $Notification->entry->children('yt', true)->channelId->__toString() ?? null;
        $title = $Notification->entry->title->__toString() ?? null;
        $name = $videoId . ' > ' . $title;
        $this->log($name);

        if (! $videoId || ! $channelId) {
            return $this->log('Invalid notification xml');
        }

        if (Channels::verifyChannel($channelId) === false) {
            return $this->log('Channel not found: ' . $channelId);
        }

        $Channel = Channels::byId($channelId);

        // todo: check if video is a trailer or teaser; if not, skip
        if (! $Channel->isTrailer($title)) {
            return $this->log('Video is not a trailer or teaser');
        }

        $Video = Video::find($videoId);
        if ($Video) {
            return $this->log('Video already exists');
        }

        $response = YouTube::video($videoId);
        if (empty($response['items'][0]['id'])) {
            return $this->log('Video not found');
        }

        try {
            $YouTubeVideo = new YouTubeVideo($response['items'][0]);
        } catch (\Exception $e) {
            return $this->log($e->getMessage());
        }
        $this->log('Video found: ' . $YouTubeVideo->id());
        try {
            $Video = $YouTubeVideo->make();
        } catch (\Exception $e) {
            return $this->log($e->getMessage());
        }
        $this->log('Video saved: ' . $Video->id);

        $Video = $this->fetchDetails($Video);

        if (! $Video->media_id) {
            return $this->log('No media found for video, type: ' . $Video->guessType());
        }

        if ($Video->media->tmdb_popularity < 5) {
        if ($Video->media->original_language != 'en') {
            return $this->log('Media is not English: ' . $Video->media->original_language);
        }

            return $this->log('Media popularity is too low: ' . $Video->media->tmdb_popularity);
        }

        $BlueSkyPost = \App\Actions\BlueSky\Post::exec(['id' => $Video->id]);
        foreach ($BlueSkyPost->log as $log) {
            ($log->error) ? $this->log('error: ' . $log->message) : $this->log($log->message);
        }

        return $this->log('End');
    }

    protected function log($message)
    {
        $log_file = storage_path('logs/yt_notifications.log');
        file_put_contents($log_file, $message . PHP_EOL, FILE_APPEND);
    }

    protected function logRequest(Request $request)
    {
        $log_file = storage_path('notifications/' . date('Y-m-d_H-i-s') . '.json');

        $data = [
            'headers' => $request->header(),
            'content' => $request->getContent(),
            'query' => $request->query(),
            'all' => $request->all(),
            'method' => $request->method(),

        ];
        file_put_contents($log_file, json_encode($data, JSON_PRETTY_PRINT));
    }
}
