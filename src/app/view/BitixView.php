<?php

namespace leona\system\app\view;
use leona\system\app\controller\PlanController;
use leona\system\app\helpers\StringUtils;

class BitixView {

    public function __construct()
    {
        $this->controller = new PlanController();
        $this->initialize();
    }

    private function initialize()
    {
        $this->sendBasicMsg('Olá! Bem Vindo ao sistema Bitix Plano de saúde');
        $this->sendBasicMsg('Nós iremos registrar o seu plano de saúde!
        Desde já, muito obrigado por nos escolher');
        $this->sendBasicMsg('Primeiro, digite o código do plano de saúde que você deseja');
        
        $code = $this->getCode();

        $this->sendBasicMsg('Perfeito! Agora, quantos beneficiários serão?');
        
        $qtd = $this->getQtd();

        $this->sendBasicMsg('Okay! Agora digite o nome de cada um deles seguido pela sua idade!');

        $persons = $this->getPersons($qtd);

        $precos = $this->controller->getPrecoTotal($code, $qtd, $persons);
        
        $this->sendBasicMsg("O preço total do plano de saúde foi de {$precos[0]} Reais");

        foreach($precos[1] as $preco) {
            $this->sendBasicMsg("O preço do plano para {$preco[0]} de {$preco[1]} anos foi de {$preco[2]} Reais");
        }
        sleep(5);

        $this->endMessage();

    }

    private function getCode(): int
    {
        $code = -1;

        while($code === -1) {
            $response = StringUtils::removeWhiteSpaces($this->getResponseFromUser());
            if(!$this->controller->verifyCode($response)) {
                $this->sendBasicMsg('O seu código é inválido ou não existe, tente outro código');
                continue;
            }
            $code = $response; 
        }
        return $code;
    }

    private function getQtd(): int
    {
        $qtd = -1;
        while($qtd === -1) {
            $response = StringUtils::removeWhiteSpaces($this->getResponseFromUser());
            if(!$this->controller->verifyQtd($response)) {
                $this->sendBasicMsg('Por favor, digite um número de beneficiários válido');
                continue;
            }
            $qtd = $response;
        }
        return $qtd;
    }

    private function endMessage() {
        $this->sendBasicMsg('Espero que tenha gostado do nosso sistema!');
        $this->sendBasicMsg('Caso queira continuar com nossos serviços, digite \'y\'. 
        Caso não queria, deixe em branco');
        $res = StringUtils::removeWhiteSpaces($this->getResponseFromUser());
        if($res==='y') {
            $this->initialize();
        } else {
            exit();
        }
    }

    private function getPersons($qtd) {

        $persons = [];

        for($i =0; $i < $qtd; $i++) {
            $this->sendBasicMsg('Digite o nome e idade do beneficiado!');
            $this->sendBasicMsg('Exemplo: Nome - idade');
            $temp = StringUtils::removeWhiteSpaces($this->getResponseFromUser());
            $tempPersons = $this->controller->verifyPersons($temp);
            if(!$tempPersons) {
                $this->sendBasicMsg('Por favor digite um beneficiário válido');
                $i--;
                continue;
            }
            $persons[]= $tempPersons;
        }

        return $persons;
    }

    private function openReadbleStream()
    {
        return  fopen('php://stdin', 'r');
    }

    private function getResponseFromUser()
    {
        return fgets($this->openReadbleStream());
    }

    private function sendBasicMsg(string $msg)
    {
        echo PHP_EOL.$msg.PHP_EOL;
    }

}