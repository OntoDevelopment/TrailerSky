<?php

namespace App\Entities\VideoType;

class Trailer extends \App\Entities\VideoType
{
    public static function enum(): string
    {
        return 'trailer';
    }

    public static function label(): string
    {
        return 'Trailer';
    }
}
