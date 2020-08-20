<?php

namespace leona\system\app\model;

use ArgumentCountError;
use ErrorException;

class Person {
    
    private string $name;
    private int $age;
    /**
     * Age should be integer, because we'll use it like a number.
     */

    public function __construct(string $name, int $age)
    {
        $this->name = $this->verifyName($name);
        $this->age = $this->verifyAge($age);
    }

    private function verifyAge(int $age): int
    {
        if($age < 0 || $age > 130) {
            throw new ErrorException('Age must be valid', 400);
        }

        return $age;
    }

    private function verifyName(string $name): string
    {
        if(mb_strlen($name) < 3) {
            throw new ErrorException('The name must have 3 or more characters');
        }

        return $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
    
    public function getAge(): int 
    {
        return $this->age;
    }
    
}