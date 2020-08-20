<?php

namespace leona\system\app\model;

use ErrorException;

class PlanoFamily extends Plano{
    
   protected array $persons;
   protected array $faixas;
   protected int $mv;
   

   public function __construct(string $reg, string $name, int $code,array $persons ,array $faixas, int $mv)
   {
       parent::__construct($reg, $name, $code);
       $this->mv = $mv;
       $this->persons = $this->verifyPersons($persons);
       $this->faixas = $this->verifyFaixas($faixas);
   }

   protected function verifyFaixas(array $faixas): array
   {
        if(count($faixas) < 3) {
            throw new ErrorException('Faixas must have more than 3 possibilitys');
        }

        return $faixas;
   }

   protected function verifyPersons(array $persons): array
   {
        if(count($persons) < $this->mv) {
            throw new ErrorException('This plan only supports four or more than four people');
        }

        return $persons;
   }

   public function getPersons(): array
   {
       return $this->persons;
   }

   public function getQtdPersons(): int
   {
       return count($this->persons);
   }

   public function getFaixa(): array
   {
       return $this->faixas;
   }
}
