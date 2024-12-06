<?php
namespace App\Entities\VideoType;

class Undefined extends \App\Entities\VideoType {
    public static function enum(): string
    {
        return 'undefined';
    }

    public static function label(): string
    {
        return 'Undefined';
    }
}