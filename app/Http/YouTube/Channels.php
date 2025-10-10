<?php

namespace App\Http\YouTube;

class Channels
{
    public static $list = [
        '20th Century Studios' => TwentiethCenturyStudios::class,
        'A24' => A24::class,
        'Apple TV' => Apple::class,
        'Blumhouse' => Blumhouse::class,
        'Briarcliff Entertainment' => Briarcliff::class,
        'DC' => DC::class,
        'Disney' => Disney::class,
        //'DreamWorks' => DreamWorks::class, // DreamWorks is part of Universal
        'Focus Features' => FocusFeatures::class,
        'HBO' => HBO::class,
        'Hulu' => Hulu::class,
        'IFC Films' => IFC::class,
        'Illumination' => Illumination::class,
        'Lionsgate Movies' => Lionsgate::class,
        'Marvel Entertainment' => Marvel::class,
        'Max' => Max::class,
        'Amazon MGM Studios' => MGM::class,
        'Neon' => Neon::class,
        'Netflix' => Netflix::class,
        'Paramount Pictures' => Paramount::class,
        'Paramount Movies' => ParamountMovies::class,
        'Paramount Plus' => ParamountPlus::class,
        'Peacock' => Peacock::class,
        'Pixar' => Pixar::class,
        'Prime Video' => Prime::class,
        'Roku Channel' => Roku::class,
        'Searchlight Pictures' => SearchlightPictures::class,
        'Sony Pictures Entertainment' => Sony::class,
        'Star Wars' => StarWars::class,
        'Universal Pictures' => Universal::class,
        'Walt Disney Animation Studios' => WaltDisneyAnimationStudios::class,
        'Warner Bros. Pictures' => WarnerBros::class,

    ];

    public static function byId($id): Channel
    {
        foreach (self::$list as $name => $class) {
            if ($class::$id == $id) {
                return new $class($name);
            }
        }
        $Channel = new Unknown;
        $Channel::$id = $id;

        return $Channel;
    }

    public static function verifyChannel($channelId): bool
    {
        return in_array($channelId, self::ids());
    }

    public static function byKey($key): ?Channel
    {
        return new self::$list[$key];
    }

    public static function ids(): array
    {
        return array_map(function ($class) {
            return $class::$id;
        }, self::$list);
    }
}
