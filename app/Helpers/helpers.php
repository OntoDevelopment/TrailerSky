<?php

use Carbon\Carbon;

if (! defined('ZULU')) {
    define('ZULU', 'Y-m-d\TH:i:s\Z');
}

function hashtag($string)
{
    $string = str_replace('&', 'And', $string);
    $string = ucwords($string);
    // Remove all characters except letters, numbers, and underscores
    $string = preg_replace('/[^a-zA-Z0-9_]/', '', $string);
    // Remove all duplicate underscores
    $string = preg_replace('/_+/', '_', $string);

    return $string;
}

function ci_compare($a, $b)
{
    return strcasecmp($a, $b) == 0;
}

function title_compare($a, $b)
{
    $a = preg_replace('/[^A-Za-z0-9 ]/', '', $a);
    $b = preg_replace('/[^A-Za-z0-9 ]/', '', $b);

    return ci_compare($a, $b);
}

function season_finds($title)
{
    $searches = [];
    for ($i = 1; $i <= 10; $i++) {
        $searches[] = ": Season $i";
        $searches[] = ": Volume $i";
        $searches[] = " Season $i";
        $searches[] = " Volume $i";
    }
    foreach ($searches as $search) {
        $stripos = stripos($title, $search);
        if ($stripos !== false) {
            return substr($title, $stripos, strlen($search));
        }
    }

    return null;
}

function is_season($title)
{
    $season = season_finds($title);

    return $season !== null;
}

function strip_season($title)
{
    $season = season_finds($title);
    if ($season) {
        return str_replace($season, '', $title);
    }

    return $title;
}

function is_current_route($name)
{
    return request()->routeIs($name);
}

function friendly_date($date)
{
    return Carbon::parse($date)->format('F j, Y');
}

function hash_query(\Illuminate\Database\Eloquent\Builder $query)
{
    $str = $query->toSql();
    $str .= json_encode($query->getBindings());

    return md5($str);
}

function sublen($string, $length)
{
    return trim(substr(trim($string), 0, $length));
}

function asci_chars($string)
{
    return preg_replace('/[^\x20-\x7E]/', '', $string);
}
