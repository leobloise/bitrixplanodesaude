<?php

namespace leona\system\app\controller;

use Error;
use leona\system\app\helpers\StringUtils;
use leona\system\app\model\Person;
use leona\system\app\model\Plano;
use leona\system\app\model\PlanoFamily;
use leona\system\app\model\PlanoSingle;
use leona\system\app\service\BitixDAO;

class PlanController {

    private int $i = -1;

    public function verifyCode(string $response): bool
    {
        if(!is_numeric($response)){
            return false;
        }

        if(!$this->verifyIfItExists($response)){
            return false;
        } else {
            return true;
        }
    }

    public function verifyQtd(string $response): bool
    {
        if(!is_numeric($response)){
           return false;
        }
        if($response <= 0){
            return false;
        }
        
        return true;
    }

    private function verifyIfItExists(int $code): bool
    {
        $bit = new BitixDAO();
        $temp = StringUtils::removeWhiteSpaces($code);
        $cc = $bit->getPrecos($temp);

        if(count($cc) === 0) {
            return false;
        } else {
            return true;
        }
    }

    public function verifyPersons(string $temp)
    {
        if(!$this->verifyIfAgeAndNameAreCorrect($temp)) {
            return false;
        } 

        $result = $this->splitPersonString('-', $temp);
            
        if(!$this->verifyFormat($result)) {
            return false;
        }

        return true;
    }



    public function splitPersonString(string $delimiter, string $text): array
    {
        $result = explode($delimiter, $text);
        return $result;
    }

    private function verifyFormat(array $result): bool
    {
        
        if(!StringUtils::validName($result[0])) {
            return false;
        }

        if(!StringUtils::validAge($result[1])) {
            return false;
        }
        
        return true;
    }

    private function verifyIfAgeAndNameAreCorrect(string $text): bool
    {
        $result = strpos($text, '-');
        if(!is_numeric($result)) {
            return false;
        }

        return true;
    }
    
    private function createPlan(string $code, string $qtd, array $persons): Plano
    {
        $bit = new BitixDAO();
        $precos = $bit->getPrecos($code);

        if(count($precos) === 1) {

            return $this->createSinglePlan([
                $precos[0]->faixa1,
                $precos[0]->faixa2,
                $precos[0]->faixa3
            ], $persons, $code, $precos[0]->minimo_vidas);           

        } else {

            return $this->createFamilyPlan($persons,$code,$qtd);
        }
    }

    private function createFamilyPlan(array $persons, int $code, int $qtd): Plano
    {
        $bit = new BitixDAO();
        $precos = $bit->getPrecos($code);
        $planos = $bit->getPlanos($code);
        $tabelPreco = [];
        foreach($precos as $key=>$preco) {

            $this->i++;
            
            if(($preco->minimo_vidas - $qtd) === 0 && $this->i == 0) {
                $tabelPreco[] = $preco;
                break;
            }
            if(($preco->minimo_vidas - $qtd) > 0 && $this->i == 0) {
                $tabelPreco[] = $preco;
                continue;
            }
            if(($qtd - $preco->minimo_vidas) < 0 && $this->i==1) {
                break;
            }
            if(($qtd - $preco->minimo_vidas) === 0 && $this->i==1) {
                array_pop($tabelPreco);
                $tabelPreco[] = $preco;
                break;
            }
            if(($qtd - $preco->minimo_vidas) > 0 && $this->i==1) {
                array_pop($tabelPreco);
                $tabelPreco[] = $preco;
                break;
            }
                $tabelPreco[] = $preco;
        }
        $faixas = [
            $tabelPreco[0]->faixa1,
            $tabelPreco[0]->faixa2,
            $tabelPreco[0]->faixa3
        ];
        $objectPerson = [];
        foreach($persons as $person) {
            $objectPerson[] = new Person($person[0], $person[1]);
        }
        if($tabelPreco[0]->minimo_vidas == 1) {
            return new PlanoSingle($planos[0]->registro, $planos[0]->nome,
            $planos[0]->codigo, $objectPerson, $faixas, $tabelPreco[0]->minimo_vidas);
        } else {
            return new PlanoFamily($planos[0]->registro, $planos[0]->nome,
            $planos[0]->codigo, $objectPerson, $faixas, $tabelPreco[0]->minimo_vidas);
        }   
    }

    private function createSinglePlan(array $faixas, array $persons, int $code, int $mv)
    {
        $bit = new BitixDAO();
        $plano = $bit->getPlanos($code);

        $objectPerson = [];

        foreach($persons as $person) {
            $objectPerson[] = new Person($person[0], $person[1]);
        }

        return new PlanoSingle($plano[0]->registro, $plano[0]->nome,
            $plano[0]->codigo, $objectPerson , $faixas, $mv);
    }

    public function getPrecoTotal(string $code, string $qtd, array $persons){

        $plano = $this->createPlan($code, $qtd, $persons);
        $eachone = $plano->eachCost();
        $total = $plano->totalCost();
        
        return [$total, $eachone];
    }
}