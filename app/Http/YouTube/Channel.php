<?php
namespace App\Http\YouTube;

use App\Http\Clients\YouTube;

use \App\Entities\VideoType;

abstract class Channel {
    public static $id;
    public static $name;
    public static $hashtags = '';
    public static $titleDividers = ['|', '–', '-'];

    public function __construct()
    {
        
    }

    public function getRecent($publishedAfter = null){
        return YouTube::search($this->queryGetRecent($publishedAfter));
    }

    public function queryGetRecent($publishedAfter = null) : array
    {
        $query = $this->query();
        $query['order'] = 'date';
        $query['part'] = 'snippet,id';
        $query['maxResults'] = 50;
        $query['type'] = 'video';
        if($publishedAfter){
            $query['publishedAfter'] = $publishedAfter;
        }
        return $query;
    }

    public function query() : array
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
        if(strpos($after, 'Classic Trailer') !== false){
            return new VideoType\Other;
        }
        if (strpos($after, 'Official Trailer') !== false) {
            return new VideoType\Trailer;
        }
        if (strpos($after, 'Teaser') !== false) {
            return new VideoType\Teaser;
        }
        if (strpos($after, 'Trailer') !== false) {
            return new VideoType\Trailer;
        }
        return new VideoType\Undefined;
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
        if(is_season($title)){
            $title = strip_season($title);
        }
        return $title;
    }

    public function titleParts($title): array
    {
        foreach(static::$titleDividers as $divider) {
            if(strpos($title, $divider) !== false) {
                return explode($divider, $title);
            }
        }

        return [$title];
    }
}