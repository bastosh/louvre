<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 20/09/2016
 * Time: 00:29
 */

namespace tests\AppBundle\Unit\Services;

use AppBundle\Entity\User;
use AppBundle\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserServiceTest extends KernelTestCase
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

    public function testCreateUser()
    {
        $em = $this->em;
        $user = new User(mt_rand().'@gmail.com');
        $us = new UserService($em);
        $us->createUser($user);

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