<?php

namespace App\Http\YouTube;

class Unknown extends Channel
{
    public static $id = '';
    public static $hashtags = '';

    public function __construct(public $name = 'Unknown')
    {
        
    }
}
