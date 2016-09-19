<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 19/09/2016
 * Time: 10:07
 */

namespace tests\AppBundle\Unit\Entity;

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

}