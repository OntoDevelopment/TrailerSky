<?php

namespace App\Http\YouTube;

class Lionsgate extends Channel
{
    public static $id = 'UCJ6nMHaJPZvsJ-HmUmj1SeA';

    public static $hashtags = '#Lionsgate';

    public function titleParts($title): array
    {
        $pos = strpos($title, '(202');

        if ($pos > 0) {
            $before = trim(substr($title, 0, $pos));
            $after = substr($title, $pos + 6);

            return [$before, $after];
        }

        return [$title];
    }
}
