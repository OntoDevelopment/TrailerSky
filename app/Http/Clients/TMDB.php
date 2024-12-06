<?php

namespace App\Http\Clients;

use \Illuminate\Support\Carbon;

class TMDB extends Client
{
    public static function base(): string
    {
        return 'https://api.themoviedb.org/3/';
    }

    public static function search($title, $query = [])
    {
        $query['query'] = $title;
        $url = self::base() . 'search/multi';
        $response = self::withHeaders(static::headers())->get($url, $query);

        if ($response->successful()) {
            return $response->json()['results'];
        }
        $response->throw();
    }

    public static function find($title, \App\Models\Video $Video)
    {
        $results = TMDB::search($title);
        // filter out results with non-matching title
        $results = array_filter($results, function ($result) use ($title) {
            if ($result['media_type'] == 'movie') {
                return ci_compare($result['title'], $title);
            } else {
                return ci_compare($result['name'], $title);
            }
        });

        // filter out non-movie/tv results
        $results = array_filter($results, function ($result) {
            return in_array($result['media_type'], ['movie', 'tv']);
        });

        if ($Video->isSeason()) {
            // filter out results with release date in the past
            $results = array_filter($results, function ($result) {
                $date_string = $result['media_type'] == 'movie' ? $result['release_date'] : $result['first_air_date'];
                return Carbon::parse($date_string)->isFuture();
            });
        }

        // sort by release date
        usort($results, function ($a, $b) {
            $a_date = $a['media_type'] == 'movie' ? $a['release_date'] : $a['first_air_date'];
            $b_date = $b['media_type'] == 'movie' ? $b['release_date'] : $b['first_air_date'];
            return $b_date <=> $a_date;
        });

        foreach ($results as $result) {
            return $result;
        }
        return null;
    }

    public static function details($id, $type)
    {
        $url = self::base() . $type . '/' . $id;
        $response = self::withHeaders(static::headers())->get($url);

        if ($response->successful()) {
            return $response->json();
        }
        $response->throw();
    }

    protected static function headers()
    {
        return [
            'Authorization' => 'Bearer ' . config('services.themoviedb.read_access_token'),
            'accept' => 'application/json',
        ];
    }
}
