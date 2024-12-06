<?php
namespace App\Entities\VideoType;

class Teaser extends \App\Entities\VideoType {
    public static function enum(): string
    {
        return 'teaser';
    }

    public static function label(): string
    {
        return 'Teaser';
    }
}