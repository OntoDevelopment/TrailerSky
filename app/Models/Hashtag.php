<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $text
 * @property int $rank
 */

class Hashtag extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $orderBy = 'rank';

    public function text() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => hashtag($value)
        );
    }

    public static function lookup($text, $default_rank = 10){
        $text = hashtag($text);
        $Hashtag = Hashtag::where('text', $text)->first();
        if (!$Hashtag) {
            $Hashtag = new Hashtag();
            $Hashtag->text = $text;
            $Hashtag->rank = $default_rank;
            $Hashtag->save();
        }
        return $Hashtag;
    }
}
