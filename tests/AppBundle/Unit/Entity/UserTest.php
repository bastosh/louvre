<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 20/09/2016
 * Time: 00:11
 */

namespace AppBundle\Unit\Entity;


use AppBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testSetAndGetStripeToken()
    {
        $user = new User('test@gmail.com');
        $user->setStripeCustomerId(1);
        $this->assertNotNull($user->getStripeCustomerId());
    }

    public function testSetAndGetId()
    {
        $user = new User('test@gmail.com');
        $user->setId(1);
        $this->assertNotNull($user->getId());
        $this->assertEquals(1, $user->getId());
    }
}