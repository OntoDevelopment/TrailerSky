<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Http\YouTube\Channels;

/**
 * @property string $id
 * @property string $name
 * @property \Illuminate\Support\Carbon  $last_query
 * @property int $wait_hours
 */
class Channel extends AppModel
{
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        $casts = parent::casts();
        $casts['last_query'] = 'datetime';
        return $casts;
    }

    public function hashtags(): string
    {
        return Channels::byId($this->id)::$hashtags;
    }

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn($value) => 'https://www.youtube.com/channel/' . $this->id
        );
    }

    public function getUrl() : string
    {
        return 'https://www.youtube.com/channel/' . $this->id;
    }
}
