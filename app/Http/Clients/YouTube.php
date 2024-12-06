<?php

namespace App\Http\Clients;

class YouTube extends Client
{
    public static function base(): string
    {
        return 'https://www.googleapis.com/youtube/v3/';
    }

    public static function search(array $query): array
    {
        $query['key'] = config('services.youtube.key');
        $query['maxResults'] = $query['maxResults'] ?? 10;
        $response = self::get(self::base() . 'search', $query);

        if ($response->successful()) {
            return $response->json()['items'];
        }
        $response->throw();
    }

    public static function video($id)
    {
        $query = [
            'id' => $id,
            'part' => 'snippet,contentDetails,statistics',
        ];
        $response = self::get(self::base() . 'videos', $query);

        return $response['items'][0];
    }
}
