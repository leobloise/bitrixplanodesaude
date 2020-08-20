<?php

namespace leona\system\app\helpers;

class PlanControllerHelper {

    public function verifyFormat(array $result): bool
    {
        
        if(!StringUtils::validName($result[0])) {
            return false;
        }

        if(!StringUtils::validAge($result[1])) {
            return false;
        }
        
        return true;
    } 

    public function verifyIfAgeAndNameAreCorrect(string $text): bool
    {
        $result = strpos($text, '-');
        if(!is_numeric($result)) {
            return false;
        }

        return true;
    } 

}