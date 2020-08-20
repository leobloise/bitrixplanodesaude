<?php

namespace leona\system\app\model;

use ArgumentCountError;
use ErrorException;

class Person {
    
    private string $name;
    private int $age;

    public function __construct(string $name, int $age)
    {
        $this->name = $this->verifyName($name);
        $this->age = $this->verifyAge($age);
    }

    private function verifyAge(int $age): int
    {
        if($age < 0 || $age > 130) {
            throw new ErrorException('Idade deve ser válida');
        }

        return $age;
    }

    private function verifyName(string $name): string
    {
        if(mb_strlen($name) < 3) {
            throw new ErrorException('O nome deve ter mais de 3 caracteres');
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