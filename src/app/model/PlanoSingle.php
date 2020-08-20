<?php

namespace leona\system\app\model;

use ErrorException;

final class PlanoSingle extends Plano {

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
            throw new ErrorException('Faixas devem ter três possibilidades');
        }

        return $faixas;
   }

   protected function verifyPersons(array $persons): array
   {
        if(count($persons) < $this->mv) {
            throw new ErrorException("O plano suporta somente $this->mv ou um pouco mais");
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
