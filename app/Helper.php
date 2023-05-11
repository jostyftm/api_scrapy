<?php

namespace App;

class Helper
{
    public static function getKeyWords($word)
    {
        $articles = array('a', 'el', 'la', 'los', 'en', 'un', 'una', 'unas', 'unos', 'con', 'de', 'que', 'se', 'va');
        
        $array = explode(' ',preg_replace('/\b('.implode('|', $articles).')\b/','', $word));

        return array_filter($array, fn($value)=> !is_null($value) && $value !== '');
    }
}