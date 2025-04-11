<?php

namespace App\Entities;

abstract class VideoType
{
    abstract public static function enum(): string;

    abstract public static function label(): string;

    public function __toString()
    {
        return static::label();
    }
}
