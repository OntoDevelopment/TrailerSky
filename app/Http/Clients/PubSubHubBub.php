<?php

namespace App\Http\Clients;

class PubSubHubBub extends Client
{
    public static function base(): string
    {
        return 'https://pubsubhubbub.appspot.com';
    }

    public static function subscribe($id)
    {
        $url = self::base() . '/subscribe';
        $data = [
            'hub.callback' => 'https://trailersky.com/subscriber/notify',
            'hub.topic' => 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $id,
            'hub.mode' => 'subscribe',
            'hub.verify' => 'sync',
        ];

        $response = self::asForm()->post($url, $data);

        // if response code == 204, then it's a success
        if ($response->status() == 204) {
            return true;
        }

        return [
            'status' => $response->status(),
            'body' => $response->body(),
        ];

        $response->throw();

        return $response->status() == 204;
    }
}
