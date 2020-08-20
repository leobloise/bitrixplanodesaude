<?php

namespace leona\system\app\helpers;

use Error;

class StringUtils {

    public function __construct()
    {
        
    }

    public static function removeWhiteSpaces(string $text): string
    {
        return preg_replace('/\s/', '', $text);
    }

    public static function validName(string $name): bool
    {
        if(is_numeric($name)){
            return false;
        }

        if(strlen($name) < 3) {
            return false;
        }

        if(preg_match('/\d/', $name) === 1) {
            return false;
        }

        if(is_numeric($name)){
            return false;
        }

        return true;
    }

    public static function validAge( $age): bool
    {
        if(!is_numeric($age)){
            return false;
        } 
        if(preg_match('/[A-z]/', $age) === 1) {
            return false;
        }

        $age = intval($age);
        
        if($age < 0 || $age > 130) {
                return false;
        }

        return true;
    }

}