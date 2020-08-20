<?php

namespace leona\system\app\service;

class BitixDAO {

    private string $pathToPlanos;
    private string $pathToPrecos;
    private $filter;
    private int $code;

    public function __construct()
    {
        $this->pathToPlanos = 'src/database/planos.json';
        $this->pathToPrecos = 'src/database/precos.json';
        
        $this->filter = function(object $value) {
            if($this->code === $value->codigo) {
                return true;
            } else {
                return false;
            }
        };
        
    }

    public function getPrecos(int $code): array
    {
        $this->code = $code;
        $precos = $this->getAllPrecos();
        //Splat operator vai 'reiniciar' a chave do array
        return [...array_filter($precos, $this->filter)];
    }

    public function getPlanos(int $code): array
    {
        $this->code = $code;
        $planos = $this->getAllPlanos();
        return [...array_filter($planos, $this->filter)];
    }

    public function getAllPrecos(): array 
    {
        return json_decode(file_get_contents($this->pathToPrecos));
    }

    public function getAllPlanos(): array 
    {
        return json_decode(file_get_contents($this->pathToPlanos));
    }
}




