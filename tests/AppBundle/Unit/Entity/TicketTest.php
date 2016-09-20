<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 19/09/2016
 * Time: 10:07
 */

namespace tests\AppBundle\Unit\Entity;

use AppBundle\Entity\Commande;
use AppBundle\Entity\Ticket;

class TicketTest extends \PHPUnit_Framework_TestCase
{

    public function testIdAttribute()
    {
        $ticket = new Ticket();
        $ticket->setId(1);
        $this->assertNotNull($ticket->getId());
        $this->assertEquals(1, $ticket->getId());

    }

    public function testIsreduced()
    {
        $ticket = new Ticket();
        $ticket->setReduced(true);
        $this->assertTrue($ticket->isReduced());
    }

    public function testVisitDate()
    {
        $ticket = new Ticket();
        $date = new \DateTime('tomorrow');
        $ticket->setVisitDate($date->format('d-m-Y'));
        $this->assertNotNull($ticket->getVisitDate());
        $this->assertEquals($date->format('d-m-Y'), $ticket->getVisitDate());
    }

    public function testSetAndGetAge()
    {
        $ticket = new Ticket();
        $ticket->setAge(4);
        $this->assertNotNull($ticket->getAge());
        $this->assertEquals(4, $ticket->getAge());
    }

    public function testSetAndGetPrice()
    {
        $ticket = new Ticket();
        $ticket->setPrice(12);
        $this->assertNotNull($ticket->getPrice());
        $this->assertEquals(12, $ticket->getPrice());
    }

    public function testSetCommande()
    {
        $commande = new Commande();
        $ticket = new Ticket();
        $ticket->setCommande($commande);
        $this->assertNotNull($ticket->getCommande());
    }

    public function testSetAndGetFirstname()
    {
        $ticket = new Ticket();
        $ticket->setFirstname('Marcel');
        $this->assertNotNull($ticket->getFirstname());
        $this->assertEquals('Marcel', $ticket->getFirstname());
    }

    public function testSetAndGetLastname()
    {
        $ticket = new Ticket();
        $ticket->setLastname('Pagnol');
        $this->assertNotNull($ticket->getLastname());
        $this->assertEquals('Pagnol', $ticket->getLastname());
    }

    public function testSetAndGetCountry()
    {
        $ticket = new Ticket();
        $ticket->setCountry('France');
        $this->assertNotNull($ticket->getCountry());
        $this->assertEquals('France', $ticket->getCountry());
    }

    public function testSetAndGetBirthday()
    {
        $ticket = new Ticket();
        $ticket->setBirthday(new \DateTime('-4y'));
        $this->assertNotNull($ticket->getBirthday());
    }

}