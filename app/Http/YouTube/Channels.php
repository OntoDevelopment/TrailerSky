<?php

namespace App\Http\YouTube;

class Channels
{
    public static $list = [
        '20th Century Studios' => TwentiethCenturyStudios::class,
        'A24' => A24::class,
        'Blumhouse' => Blumhouse::class,
        'Briarcliff Entertainment' => Briarcliff::class,
        'Disney' => Disney::class,
        //'DreamWorks' => DreamWorks::class, // DreamWorks is part of Universal
        'Focus Features' => FocusFeatures::class,
        'HBO' => HBO::class,
        'Hulu' => Hulu::class,
        'IFC Films' => IFC::class,
        'Illumination' => Illumination::class,
        'Lionsgate Movies' => Lionsgate::class,
        'Marvel Entertainment' => Marvel::class,
        'Amazon MGM Studios' => MGM::class,
        'Neon' => Neon::class,
        'Netflix' => Netflix::class,
        'Paramount Pictures' => Paramount::class,
        'Peacock' => Peacock::class,
        'Pixar' => Pixar::class,
        'Prime Video' => Prime::class,
        'Roku Channel' => Roku::class,
        'Searchlight Pictures' => SearchlightPictures::class,
        'Sony Pictures Entertainment' => Sony::class,
        'Star Wars' => StarWars::class,
        'Universal Pictures' => Universal::class,
        'Warner Bros. Pictures' => WarnerBros::class,

    ];

    public static function byId($id) : ?Channel
    {
        foreach (self::$list as $name => $class) {
            $class::$name = $name;
            if ($class::$id == $id) {
                return new $class;
            }
        }
        return null;
    }

    public static function byKey($key) : ?Channel
    {
        return new self::$list[$key];
    }
}
