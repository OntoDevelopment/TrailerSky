<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Orchid\Filters\Types as Filters;

/**
 * @property string $title
 * @property int $tmdb_id
 * @property float $tmdb_popularity
 * @property string $media_type (movie|tv)
 * @property string $imdb_id
 * @property string $release_date
 * @property string $tmdb_poster_path
 * @property Hashtag[] $hashtags
 * @property Video[] $videos
 * @property string $poster_url
 * @property string $tmdb_url
 */
class Media extends AppModel
{
    protected $allowedFilters = [
        'title' => Filters\Like::class,
        'media_type' => Filters\WhereIn::class,
        'created_at' => Filters\WhereDateStartEnd::class,
        'updated_at' => Filters\WhereDateStartEnd::class,
    ];

    protected $allowedSorts = [
        'title',
        'media_type',
        'created_at',
        'updated_at',
    ];

    // hasmany hashtags through media_hashtags
    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'media_hashtags')->orderBy('rank', 'desc');
    }

    // hasmany videos
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function title(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => html_entity_decode($value),
            set: fn ($value) => html_entity_decode($value)
        );
    }

    public function posterUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getTmdbPosterUrl()
        );
    }

    public function getTmdbPosterUrl($size = 'w342'): string
    {
        if ($this->tmdb_poster_path) {
            return 'https://image.tmdb.org/t/p/' . $size . $this->tmdb_poster_path;
        }

        return false;
    }

    public function tmdbUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getTmdbUrl()
        );
    }

    public function getTmdbUrl(): string
    {
        if ($this->media_type == 'movie') {
            return 'https://www.themoviedb.org/movie/' . $this->tmdb_id;
        } else {
            return 'https://www.themoviedb.org/tv/' . $this->tmdb_id;
        }
    }
}
