<?php

require_once './vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use leona\system\app\model\Person;

class PersonTest extends TestCase {
    
    /**
     * @dataProvider person
     */
    public function testCreatePerson(string $name, int $age): void 
    {   
        $person = new Person($name, $age);
 
        $this->assertEquals($name, $person->getName());
        $this->assertEquals($age, $person->getAge());
    }

    public function person() {
        return [
            ['Leonardo', 19]
        ];
    }


}
