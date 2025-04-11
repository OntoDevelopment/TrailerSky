<?php

namespace App\Entities\VideoType;

class Other extends \App\Entities\VideoType
{
    public static function enum(): string
    {
        return 'other';
    }

    public static function label(): string
    {
        return 'Other';
    }
}
