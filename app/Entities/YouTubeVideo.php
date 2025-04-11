<?php

namespace App\Entities;

use App\Http\YouTube\Channels;
use App\Models\Video;
use Illuminate\Support\Carbon;

class YouTubeVideo
{
    public $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function id(): string
    {
        return is_array($this->data['id']) ? $this->data['id']['videoId'] : $this->data['id'];
    }

    public function snippet(): array
    {
        return $this->data['snippet'];
    }

    public function channelId(): string
    {
        return $this->snippet()['channelId'];
    }

    public function title(): string
    {
        return $this->snippet()['title'];
    }

    public function description(): string
    {
        return $this->snippet()['description'];
    }

    public function publishedAt(): string
    {
        return $this->snippet()['publishedAt'];
    }

    public function thumbnails(): array
    {
        return $this->snippet()['thumbnails'];
    }

    public function thumbnail(): ?array
    {
        $t = $this->thumbnails();
        if (isset($t['maxres'])) {
            return $t['maxres'];
        }
        if (isset($t['high'])) {
            return $t['high'];
        }
        if (isset($t['medium'])) {
            return $t['medium'];
        }
        if (isset($t['default'])) {
            return $t['default'];
        }

        return null;
    }

    public function thumbnailUrl(): ?string
    {
        return $this->thumbnail()['url'] ?? null;
    }

    // App specific methods
    public function channel(): ?\App\Http\YouTube\Channel
    {
        return Channels::byId($this->channelId());
    }

    public function type(): \App\Entities\VideoType
    {
        return $this->channel()->videoType($this->title());
    }

    public function created_at(): Carbon
    {
        return Carbon::parse($this->publishedAt());
    }

    public function model(): Video
    {
        $Video = Video::find($this->id());
        if (! $Video) {
            $Video = $this->make();
        }

        return $Video;
    }

    public function make(): Video
    {
        $Video = new Video;
        $Video->id = $this->id();
        $Video->title = $this->title();
        $Video->description = $this->description();
        $Video->type = $this->type()->enum();
        $Video->channel_id = $this->channelId();
        $Video->created_at = $this->created_at();
        $Video->save();

        return $Video;
    }
}
