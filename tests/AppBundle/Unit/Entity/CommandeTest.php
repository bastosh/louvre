<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 19/09/2016
 * Time: 10:44
 */

namespace tests\AppBundle\Unit\Entity;


use AppBundle\Entity\Commande;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\User;

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

    public function testSetTicket()
    {
        $commande = new Commande();
        $ticket = new Ticket();
        $ticket->setId(10);
        $this->assertNotNull($commande->getTickets());
        $this->assertNotNull($commande->setTickets($ticket));
        $this->assertEquals($ticket->getCommande(), $commande->getId());
    }

    public function testGetUser()
    {
        $user = new User('user@test.com');
        $commande = new Commande();
        $commande->setUser($user);
        $this->assertNotNull($commande->getUser());
    }

    public function testGetCreatedAt()
    {
        $commande = new Commande();
        $this->assertNotNull($commande->getCreatedAt());
    }

    public function testSetCreatedAt()
    {
        $commande = new Commande();
        $commande->setCreatedAt(new \DateTime('tomorrow'));
        $this->assertNotNull($commande->getCreatedAt());
    }

    public function testAddTicket()
    {
        $ticket = new Ticket();
        $commande = new Commande();
        $this->assertNotNull($commande->addTicket($ticket));
    }

    public function testRemoveTicket()
    {
        $ticket = new Ticket();
        $commande = new Commande();
        $commande->addTicket($ticket);
        $commande->removeTicket($ticket);
        $this->assertNotContains($ticket, $commande->getTickets());
    }


}