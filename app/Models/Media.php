<?php

namespace App\Models;

/**
 * @property string $title
 * @property int $tmdb_id
 * @property float $tmdb_popularity
 * @property string $media_type (movie|tv)
 * @property string $imdb_id
 * @property string $release_date
 * @property Hashtag[] $hashtags
 */
class Media extends AppModel
{
    // hasmany hashtags through media_hashtags
    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'media_hashtags')->orderBy('rank', 'desc');
    }
}
