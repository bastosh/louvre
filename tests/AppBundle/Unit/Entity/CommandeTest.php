<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 19/09/2016
 * Time: 10:44
 */

namespace tests\AppBundle\Unit\Entity;


use AppBundle\Entity\Commande;

class CommandeTest extends \PHPUnit_Framework_TestCase
{
    public function testAmount()
    {
        $commande = new Commande();
        $commande->setAmount(20);
        $this->assertNotNull($commande->getAmount());
        $this->assertEquals(20, $commande->getAmount());
    }

    public function testSetToken()
    {
        $commande = new Commande();
        $commande->setToken('abc123');
        $this->assertNotNull($commande->getToken());
        $this->assertEquals('abc123', $commande->getToken());
    }
}