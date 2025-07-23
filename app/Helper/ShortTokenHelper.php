<?php


namespace App\Helper;

class ShortTokenHelper
{
    protected static $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function encode($number)
    {
        $base = strlen(self::$alphabet);
        $encoded = '';

        while ($number > 0) {
            $encoded = self::$alphabet[$number % $base] . $encoded;
            $number = floor($number / $base);
        }

        return $encoded;
    }

    public static function decode($encoded)
    {
        $base = strlen(self::$alphabet);
        $length = strlen($encoded);
        $number = 0;

        for ($i = 0; $i < $length; $i++) {
            $number = $number * $base + strpos(self::$alphabet, $encoded[$i]);
        }

        return $number;
    }
}
