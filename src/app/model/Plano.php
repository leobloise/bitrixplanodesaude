<?php

namespace leona\system\app\model;

use ErrorException;

abstract class Plano {
    
    protected string $reg;
    protected string $name;
    protected int $code;
    private $reduce;

    public function __construct(string $reg, string $name, int $code)
    {
        $this->reg = $this->verifyReg($reg);
        $this->name = $this->verifyName($name);
        $this->code = $this->verifyCode($code);
    }

    private function verifyReg(string $reg)
    {
        if(strpos($reg, 'reg') !== 0) {
            throw new ErrorException('Register is not valid');
        }

        return $reg;
    }

    private function verifyName(string $name)
    {
        if(strpos($name, 'Bitix Customer Plano') !== 0) {
            throw new ErrorException('Name is not valid');
        }

        return $name;
    }

    
    private function verifyCode(int $code)
    {
        if($code <= 0) {
            throw new ErrorException('Code is not valid');
        }

        return $code;
    }

    public function eachCost(): array
    {
         $results = [];
         foreach($this->persons as $person) {
 
             if($person->getAge()  >= 0 && $person->getAge() <= 17) {
                 $results[] = [
                     $person->getName(),
                     $person->getAge(),
                     $this->faixas[0]
                 ];
                 continue;
             }
 
             if($person->getAge() <= 40) {
                 $results[] = [
                    $person->getName(),
                     $person->getAge(),
                     $this->faixas[1]
                 ];
                 continue;
             }
 
             $results[] = [
                $person->getName(),
                $person->getAge(),
                 $this->faixas[2]
             ];
 
         }
 
         return $results;
    }

    public function totalCost() 
    {
       $this->reduce = function($carry, $one){
            $carry +=  $one[2];
            return $carry;
       };

      $eachOne = $this->eachCost();  

      return(array_reduce($eachOne, $this->reduce));
    }

    abstract protected function verifyFaixas(array $faixas): array;
}