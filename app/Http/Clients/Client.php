<?php

namespace App\Http\Clients;

abstract class Client extends \Illuminate\Support\Facades\Http
{
    abstract public static function base(): string;
}
