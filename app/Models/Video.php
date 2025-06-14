<?php

namespace App\Models;

use App\Http\Clients\TMDB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Orchid\Filters\Types as Filters;

/**
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $type
 * @property string $channel_id
 * @property string $media_id
 * @property string $dismissed_at
 * @property Channel $channel
 * @property Media $media
 * @property Post[] $posts
 */
class Video extends AppModel
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $allowedFilters = [
        'title' => Filters\Like::class,
        'description' => Filters\Like::class,
        'type' => Filters\WhereIn::class,
        'created_at' => Filters\WhereDateStartEnd::class,
        'updated_at' => Filters\WhereDateStartEnd::class,
    ];

    protected $allowedSorts = [
        'title',
        'type',
        'created_at',
        'updated_at',
    ];

    public function title(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => sublen(html_entity_decode(asci_chars($value)), 100),
            get: fn ($value) => html_entity_decode($value)
        );
    }

    public function description(): Attribute
    {
        return $this->_makeAttributeMaxLength(256);
    }

    public function guessType($unknown = 'unknown'): string
    {
        $type = TMDB::guessType($this);

        return $type ? $type : $unknown;
    }

    // Relationships

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function channelName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->channel->name ?? 'unknown'
        );
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Scopes

    public function scopeToPost(Builder $query): void
    {
        $query->whereIn('videos.type', ['trailer', 'teaser'])
            ->whereNotNull('videos.media_id')
            ->where('videos.created_at', '>', now()->subDays(6))
            //->orderBy('created_at', 'desc')
            // has no posts
            ->whereDoesntHave('posts')
            ->limit(50);
    }

    public function scopeToReview(Builder $query): void
    {
        $query->whereIn('type', ['trailer', 'teaser'])
            ->whereNull('media_id')
            ->whereNull('dismissed_at')
            ->where('created_at', '>', now()->subDays(2))
            ->orderBy('updated_at', 'desc')
            ->limit(25);
    }

    public function scopeFrontEnd(Builder $query): void
    {
        $query->whereNotNull('media_id')->whereNull('dismissed_at');
    }

    public function scopeNewest(Builder $query, $limit = 8): void
    {
        $query->orderBy('videos.created_at', 'desc')
            ->frontEnd()
            ->joinMedia()
            ->select('videos.*')
            ->limit($limit);
    }

    public function scopeNewestMovies(Builder $query, $limit = 12): void
    {
        $query->whereRaw('media.media_type = "movie"')->newest($limit);
    }

    public function scopeNewestShows(Builder $query, $limit = 8): void
    {
        $query->whereRaw('media.media_type = "tv"')->newest($limit);
    }

    public function scopeHottest(Builder $query, $limit = 8, $weight = 2): void
    {
        $query->frontEnd();
        // join media
        $query->joinMedia();
        // hot = media.tmdb_popularity - (hours since created * 2)
        $query->selectRaw('videos.*, media.tmdb_popularity - (TIMESTAMPDIFF(HOUR, videos.created_at, NOW()) * ?) as hot', [$weight]);
        $query->where('media.release_date', '>', now());
        $query->orderBy('hot', 'desc')->limit($limit);
    }

    public function scopeHot(Builder $query, $limit = 8, $weight = 20): void
    {
        $query->hottest($limit, $weight)
            ->whereRaw('media.tmdb_popularity - (TIMESTAMPDIFF(HOUR, videos.created_at, NOW()) * ?) > 0', [$weight]);
    }

    public function scopeHottestMovies(Builder $query, $limit = 8)
    {
        $query->whereRaw('media.media_type = "movie"')->hottest($limit);
    }

    public function scopeHottestShows(Builder $query, $limit = 8)
    {
        $query->whereRaw('media.media_type = "tv"')->hottest($limit);
    }

    public function scopeJoinMedia(Builder $query): void
    {
        $query->join('media', 'videos.media_id', '=', 'media.id');
    }

    // Social Media Posts

    public function postGenerate($max_characters = 300, $with_url = false): string
    {
        $post = $this->media->title;

        foreach ($this->hashtags() as $hashtag) {
            if (strlen($post) + strlen($hashtag) + 1 <= $max_characters) {
                $post .= ' ' . $hashtag;
            } else {
                break;
            }
        }
        if ($with_url) {
            $post .= ' ' . $this->youtubeUrl();
        }

        return $post;
    }

    public function postBlueSky()
    {
        $text = $this->postGenerate(300);

        return 'https://bsky.app/intent/compose?text=' . urlencode($text);
    }

    // Other
    public function hashtags()
    {
        $hashtags = [];
        $hashtags[] = '#TrailerSky';
        if ($this->media->media_type == 'movie') {
            $hashtags[] = ($this->type == 'trailer') ? '#MovieTrailer' : '#MovieTeaser';
            $hashtags[] = '#FilmSky';
        } elseif ($this->media->media_type == 'tv') {
            $hashtags[] = ($this->type == 'trailer') ? '#ShowTrailer' : '#ShowTeaser';
        }
        if ($this->channel) {
            $hashtags[] = $this->channel->hashtags();
        }
        $hashtags[] = '#' . hashtag($this->media->title);
        foreach ($this->media->hashtags as $hashtag) {
            $hashtags[] = '#' . $hashtag->text;
        }

        return $hashtags;
    }

    public function youtubeUrl()
    {
        return 'https://youtu.be/' . $this->id;
    }

    /**
     * @param  string  $size  (mqdefault, maxresdefault, sddefault, hqdefault, default)
     * @return string
     */
    public function youtubeThumbnail(string $size = 'mqdefault')
    {
        return "https://i.ytimg.com/vi/{$this->id}/{$size}.jpg";
    }

    public function youtubeThumbnailPath()
    {
        \App\Helpers\Thumbnail::get($this->id);

        return \App\Helpers\Thumbnail::fullPath($this->id);
    }

    public function isSeason()
    {
        return is_season($this->title);
    }

    public function ago()
    {
        return $this->created_at->diffForHumans();
    }
}
