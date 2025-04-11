<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property string $text
 * @property int $rank
 */
class Hashtag extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $orderBy = 'rank';

    public function text(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => hashtag($value)
        );
    }

    public static function lookup($text, $default_rank = 10)
    {
        $text = hashtag($text);
        $Hashtag = Hashtag::where('text', $text)->first();
        if (! $Hashtag) {
            $Hashtag = new Hashtag;
            $Hashtag->text = $text;
            $Hashtag->rank = $default_rank;
            $Hashtag->save();
        }

        return $Hashtag;
    }

    public function scopeOrderByRank(Builder $query)
    {
        $query->orderBy($this->orderBy);
    }

    public function scopeRelated(Builder $query, $media_id, $limit = 10)
    {
        // join

    }

    public function scopeTop(Builder $query, $limit = 10)
    {
        // query media_hashtags table for top hashtags
        $top = DB::table('media_hashtags')
            ->select('hashtag_id', DB::raw('count(*) as count'))
            ->groupBy('hashtag_id')
            ->orderBy('count', 'desc')
            ->get();
        $query->whereIn('id', $top->pluck('hashtag_id'))->where('rank', '>', 9)->orderBy('rank', 'DESC')->limit($limit);
    }
}
