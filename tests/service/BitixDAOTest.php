<?php

require_once './vendor/autoload.php';

use leona\system\app\service\BitixDAO;
use PHPUnit\Framework\TestCase;

class BitixiDAOTest extends TestCase {

    /**
     * @dataProvider codes
     */

    public function testGetPrecosFromCode(int $code, int $qtd)
    {
        $bitix = new BitixDAO();

        $precos = $bitix->getPrecos($code);

        $this->assertEquals($qtd, count($precos));
        
        for($i = 0; $i < $qtd; $i++) {
            $this->assertEquals($code, $precos[$i]->codigo);
        }
    }


    public function codes() {

        return[

            [1, 2],
            [2,1],
            [6, 2]
        
        ];
    }

}