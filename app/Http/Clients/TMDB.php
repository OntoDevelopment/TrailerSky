<?php

namespace App\Http\Clients;

use App\Models\Video;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Cache;

class TMDB extends Client
{
    public static function base(): string
    {
        return 'https://api.themoviedb.org/3/';
    }

    public static function search($title, $type, $query = [])
    {
        $query['query'] = $title;
        switch ($type) {
            case 'movie':
                $url = self::base() . 'search/movie';
                break;
            case 'tv':
                $url = self::base() . 'search/tv';
                break;
            default:
                $url = self::base() . 'search/multi';
                break;
        }
        $response = self::withHeaders(static::headers())->get($url, $query);

        if ($response->successful()) {
            return $response->json()['results'];
        }
        $response->throw();
    }

    public static function guessType(Video $Video)
    {
        $str = $Video->title . $Video->description;
        if (stripos($str, 'all episodes') !== false) {
            return 'tv';
        }
        if (stripos($str, 'in theater') !== false) {
            return 'movie';
        }
        if (stripos($str, 'episodes') !== false) {
            return 'tv';
        }

        return false;
    }

    public static function find($title, Video $Video)
    {
        $media_type = self::guessType($Video);
        $results = TMDB::search($title, $media_type);
        // set media type to movie if it's a movie
        $results = array_map(function ($result) use ($media_type) {
            if (empty($result['media_type'])) {
                $result['media_type'] = $media_type;
            }

            return $result;
        }, $results);
        // filter out results with non-matching title
        $results = array_filter($results, function ($result) use ($title) {
            if ($result['media_type'] == 'movie') {
                return title_compare($result['title'], $title);
            } else {
                return title_compare($result['name'], $title);
            }
        });

        // filter out non-movie/tv results
        $results = array_filter($results, function ($result) {
            return in_array($result['media_type'], ['movie', 'tv']);
        });

        $results = array_filter($results, function ($result) {
            if ($result['media_type'] == 'tv') {
                return true;
            }
            if (empty($result['release_date'])) {
                return true; // release date is probably not announced yet or input into TMDB
            }
            // if release date is in the future, return true
            if (! empty($result['release_date']) && Carbon::parse($result['release_date'])->isFuture()) {
                return true;
            }

            return false;
        });

        // sort by release date
        usort($results, function ($a, $b) {
            return self::bestAirdate($b) <=> self::bestAirdate($a);
        });

        foreach ($results as $result) {
            return $result;
        }

        return null;
    }

    public static function bestAirdate($details)
    {
        if (! empty($details['media_type']) && $details['media_type'] == 'movie' && ! empty($details['release_date'])) {
            return $details['release_date'];
        }
        if (! empty($details['next_episode_to_air']) && ! empty($details['next_episode_to_air']['air_date'])) {
            return $details['next_episode_to_air']['air_date'];
        }
        if (! empty($details['last_episode_to_air']) && ! empty($details['last_episode_to_air']['air_date'])) {
            return $details['last_episode_to_air']['air_date'];
        }
        if (! empty($details['first_air_date'])) {
            return $details['first_air_date'];
        }

        return null;
    }

    public static function details($id, $type)
    {
        $url = self::base() . $type . '/' . $id . '?append_to_response=credits';
        return Cache::remember($url, 60 * 60, function () use ($url) {
            $response = self::withHeaders(static::headers())->get($url);

            if ($response->successful()) {
                return $response->json();
            }
            dd("Error fetching TMDB details for $url", $response->status(), $response->body());
            $response->throw();
        });
    }

    protected static function headers()
    {
        return [
            'Authorization' => 'Bearer ' . config('services.themoviedb.read_access_token'),
            'accept' => 'application/json',
        ];
    }
}
