<?php

namespace App\Http\YouTube;

use App\Entities\VideoType;
use App\Http\Clients\YouTube;

abstract class Channel
{
    public static $id;

    public static $hashtags = '';

    public static $titleDividers = ['|', '–', '—', '-'];

    public function __construct(public $name = '') {}

    public function getRecent($publishedAfter = null)
    {
        return YouTube::search($this->queryGetRecent($publishedAfter))['items'];
    }

    public function queryGetRecent($publishedAfter = null): array
    {
        $query = $this->query();
        $query['order'] = 'date';
        $query['part'] = 'snippet,id';
        $query['maxResults'] = 50;
        $query['type'] = 'video';
        if ($publishedAfter) {
            $query['publishedAfter'] = $publishedAfter;
        }

        return $query;
    }

    public function query(): array
    {
        return [
            'channelId' => static::$id,
        ];
    }

    public function videoType($title): \App\Entities\VideoType
    {
        // if $title has | in it, then return the string after the |, else return null
        $parts = $this->titleParts($title);
        if (count($parts) < 2) {
            return new VideoType\Other;
        }
        $after = $parts[1];
        if (stripos($after, 'Classic Trailer') !== false) {
            return new VideoType\Other;
        }
        if (stripos($after, 'Official Trailer') !== false) {
            return new VideoType\Trailer;
        }
        if (stripos($after, 'Teaser') !== false) {
            return new VideoType\Teaser;
        }
        if (stripos($after, 'Trailer') !== false) {
            return new VideoType\Trailer;
        }

        return new VideoType\Undefined;
    }

    public function isTrailer($title): bool
    {
        return in_array($this->videoType($title)::enum(), ['trailer', 'teaser']);
    }

    public function videoMediaTitle($title): ?string
    {
        // if $title has | in it, then return the string before the |, else return null
        $parts = $this->titleParts($title);
        if (count($parts) < 1) {
            return $title;
        }
        $title = trim($parts[0]);
        $title = html_entity_decode($title);
        if (is_season($title)) {
            $title = strip_season($title);
        }

        return $title;
    }

    public function titleParts($title): array
    {
        foreach (static::$titleDividers as $divider) {
            if (stripos($title, $divider) !== false) {
                return explode($divider, $title);
            }
        }

        return [$title];
    }

    public function name(): string
    {
        return $this->name ?? '';
    }

    public function id(): string
    {
        return static::$id;
    }

    public function hashtags(): string
    {
        return static::$hashtags;
    }
}
