<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Casts\Attribute;

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

    public function title(): Attribute
    {
        return $this->_makeAttributeMaxLength(100);
    }

    public function description(): Attribute
    {
        return $this->_makeAttributeMaxLength(256);
    }

    // Relationships

    public function channel()
    {
        return $this->belongsTo(Channel::class);
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
        $query->whereIn('type', ['trailer', 'teaser'])
        ->whereNotNull('media_id')
        ->whereNull('dismissed_at')
        ->orderBy('created_at', 'desc')
        // has no posts
        ->whereDoesntHave('posts')
        ->limit(50);
    }

    public function scopeToReview(Builder $query): void
    {
        $query->whereIn('type', ['trailer', 'teaser'])->whereNull('media_id')->whereNull('dismissed_at')->orderBy('updated_at', 'desc')->limit(25);
    }


    // Social Media Posts

    public function postGenerate($max_characters = 300, $with_url = true): string
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
            $post .= " " . $this->youtubeUrl();
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
        } else if ($this->media->media_type == 'tv') {
            $hashtags[] = ($this->type == 'trailer') ? '#ShowTrailer' : '#ShowTeaser';
        }
        $hashtags[] = $this->channel->hashtags();
        foreach ($this->media->hashtags as $hashtag) {
            $hashtags[] = '#' . $hashtag->text;
        }

        return $hashtags;
    }

    public function youtubeUrl()
    {
        return 'https://youtu.be/' . $this->id;
    }

    public function youtubeThumbnail()
    {
        return 'https://i.ytimg.com/vi/' . $this->id . '/maxresdefault.jpg';
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
}
