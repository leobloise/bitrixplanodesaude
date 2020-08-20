<?php

require_once './vendor/autoload.php';

use leona\system\app\model\{Person, PlanoSingle, PlanoFamily};
use PHPUnit\Framework\TestCase;

class PlanoTest extends TestCase {

    /**
     * @dataProvider plans
     */

    public function testCreateSinglePlan(string $reg, string $name, int $code, array $pessoa ,array $faixa, int $mv)
    {
    
        $plano = new PlanoSingle($reg, $name, $code,$pessoa,$faixa, $mv);
        
        $this->assertEquals($reg, $plano->getReg());
        $this->assertEquals($name, $plano->getName());
        $this->assertEquals($code, $plano->getCode());
        $this->assertEquals($faixa[0], $plano->getFaixa()[0]);
        $this->assertEquals($faixa[1], $plano->getFaixa()[1]);
        $this->assertEquals($faixa[2], $plano->getFaixa()[2]);
        $this->assertEquals(count($pessoa), $plano->getQtdPersons());
    }

     /**
     * @dataProvider plansFamily
     */

    public function testCreateFamilyPlan(string $reg, string $name, int $code, array $pessoa ,array $faixa, int $mv)
    {
        $plano = new PlanoFamily($reg, $name, $code,$pessoa,$faixa, $mv);
        
        $this->assertEquals($reg, $plano->getReg());
        $this->assertEquals($name, $plano->getName());
        $this->assertEquals($code, $plano->getCode());
        $this->assertEquals($faixa[0], $plano->getFaixa()[0]);
        $this->assertEquals($faixa[1], $plano->getFaixa()[1]);
        $this->assertEquals($faixa[2], $plano->getFaixa()[2]);
        $this->assertEquals(count($pessoa), $plano->getQtdPersons());

        
    }

    /**
     * @dataProvider totalCost
     */

    public function testCalculatePlanValue(string $reg, string $name, int $code, array $pessoa ,array $faixa, int $mv) {
        
        $plano = new PlanoFamily($reg, $name, $code,$pessoa,$faixa, $mv);      
        $this->assertEquals(36, $plano->totalCost());
    }

    public function plans() {
        return [

            ['reg1', 'Bitix Customer Plano 1', 1, [new Person('leonardo', '10')],[10.00, 12, 15.00], 1],
            ['reg2', 'Bitix Customer Plano 2', 2, [new Person('leonardo', '10'), new Person('leonardo', '10')],[ 9, 11, 14.00], 1]

        ];
    }

     public function plansFamily()
    {
        return [

            ['reg1', 'Bitix Customer Plano 1', 1, ['pesosaA', 'pessoaB', 'pessoaC', 'pessoaD'],[10.00, 12, 15.00], 4],
            ['reg2', 'Bitix Customer Plano 2', 2, ['pesosaA', 'pessoaB', 'pessoaC', 'pessoaD'],[ 9, 11, 14.00], 1]

        ];
    }

    public function totalCost() {
        return [
            ['reg1', 'Bitix Customer Plano 1', 1, [new Person('leonardo', '10'), 
            new Person('leonardo', '10'),new Person('leonardo', '10')
            , new Person('leonardo', '10')],[9.00, 11, 14.00], 4]
        ];
    }
}