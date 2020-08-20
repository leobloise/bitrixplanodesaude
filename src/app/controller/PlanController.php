<?php

namespace leona\system\app\controller;

use leona\system\app\helpers\PlanControllerHelper;
use leona\system\app\helpers\StringUtils;
use leona\system\app\model\Person;
use leona\system\app\model\Plano;
use leona\system\app\model\PlanoFamily;
use leona\system\app\model\PlanoSingle;
use leona\system\app\service\BitixDAO;

class PlanController {

    private int $i;
    private BitixDAO $bit;
    private PlanControllerHelper $pch;

    public function __construct()
    {
        $this->bit = new BitixDAO();
        $this->pch = new PlanControllerHelper();
    }

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

    public function verifyPersons(string $temp)
    {
        if(!$this->pch->verifyIfAgeAndNameAreCorrect($temp)) {
            return false;
        } 

        $result = StringUtils::splitPersonString('-', $temp);
            
        if(!$this->pch->verifyFormat($result)) {
            return false;
        }

        return $result;
    }


    public function getPrecoTotal(string $code, string $qtd, array $persons){

        $plano = $this->createPlan($persons, $code, $qtd);
        $eachone = $plano->eachCost();
        $total = $plano->totalCost();
        
        return [$total, $eachone];
    }

    private function createPlan(array $persons, int $code, int $qtd): Plano
    {
        $precos =  $this->bit->getPrecos($code); 
        $planos =  $this->bit->getPlanos($code); 
        $tabelPreco = $this->getRightTabelPreco($precos, $qtd);
        $objectPerson = $this->constructPersons($persons);
        $faixas = [
            $tabelPreco[0]->faixa1,
            $tabelPreco[0]->faixa2,
            $tabelPreco[0]->faixa3
        ];
        if($tabelPreco[0]->minimo_vidas == 1) {
            return new PlanoSingle($planos[0]->registro, $planos[0]->nome,
            $planos[0]->codigo, $objectPerson, $faixas, $tabelPreco[0]->minimo_vidas);
        } else {
            return new PlanoFamily($planos[0]->registro, $planos[0]->nome,
            $planos[0]->codigo, $objectPerson, $faixas, $tabelPreco[0]->minimo_vidas);
        }   
    }

    private function constructPersons(array $persons): array
    {
        $objectPerson = [];

        foreach($persons as $person) {
            $objectPerson[] = new Person($person[0], $person[1]);
        }

        return $objectPerson;
    }

    private function getRightTabelPreco(array $precos, int $qtd): array
    {
        $this->i = -1;
        $tabelPreco = [];
        foreach($precos as $preco) {
            $this->i++;
            echo $this->i;
            if(($preco->minimo_vidas - $qtd) === 0 && $this->i == 0) {
                echo "to aqui no 0 0passada";
                $tabelPreco[] = $preco;
                break;
            }
            if(($preco->minimo_vidas - $qtd) > 0 && $this->i == 0) {
                echo "to aqui no >0 0passada";
                $tabelPreco[] = $preco;
                continue;
            }
            if(($qtd - $preco->minimo_vidas) < 0 && $this->i==1) {
                echo "to aqui no <0 1passada";
                break;
            }
            if(($qtd - $preco->minimo_vidas) === 0 && $this->i==1) {
                echo "to aqui no =0 1passada";
                array_pop($tabelPreco);
                $tabelPreco[] = $preco;
                break;
            }
            if(($qtd - $preco->minimo_vidas) > 0 && $this->i==1) {
                echo "to aqui no >0 1passada";
                array_pop($tabelPreco);
                $tabelPreco[] = $preco;
                break;
            }
            echo "to aqui no sem match";
                $tabelPreco[] = $preco;
        }
        return $tabelPreco;
    }
    
    private function verifyIfItExists(int $code): bool
    {
        $temp = StringUtils::removeWhiteSpaces($code);
        $precos = $this->bit->getPrecos($temp); 

        if(count($precos) === 0) {
            return false;
        } else {
            return true;
        }
    }
}