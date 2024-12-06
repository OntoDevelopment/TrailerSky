<?php

function hashtag($string){
    $string = str_replace('&', 'And', $string);
    $string = ucwords($string);
    // Remove all characters except letters, numbers, and underscores
    $string = preg_replace('/[^a-zA-Z0-9_]/', '', $string);
    // Remove all duplicate underscores
    $string = preg_replace('/_+/', '_', $string);
    return $string;
}

function ci_compare($a, $b){
    return strcasecmp($a, $b) == 0;
}

function is_season($title){
    $searches = [': Season', ': Volume'];
    foreach($searches as $search){
        if(strpos($title, $search) !== false){
            return true;
        }
    }
    return false;
}

function strip_season($title){
    $searches = [': Season', ': Volume'];
    foreach($searches as $search){
        if(strpos($title, $search) !== false){
            return explode($search, $title)[0];
        }
    }
    return $title;
}