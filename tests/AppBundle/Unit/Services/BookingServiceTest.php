<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 19/09/2016
 * Time: 18:27
 */

namespace tests\AppBundle\Unit\Services;

use AppBundle\Entity\Commande;
use AppBundle\Services\BookingService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookingServiceTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testEntityManager()
    {
        $commande = $this->em
            ->getRepository('AppBundle:Commande')
            ->find(1)
        ;

        $this->assertEquals(1, $commande->getId());
    }

    public function testCreateCommande()
    {
        $em = $this->em;
        $bs = new BookingService($em, 0, 8, 10, 16, 12);
        $commande = $bs->createCommande(new \DateTime('today'), 'full', 'test@gmail.com');
        $this->assertEquals(new \DateTime('today'), $commande->getVisitDate());
        $this->assertEquals('full', $commande->getType());
        $this->assertEquals('test@gmail.com', $commande->getEmail());
    }

    public function testCreateTicket()
    {
        $em = $this->em;
        $bs = new BookingService($em, 0, 8, 10, 16, 12);
        $bs->createTicket(1, 'Marcel', 'Pagnol', 'France', new \DateTime('-50y'), false);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
