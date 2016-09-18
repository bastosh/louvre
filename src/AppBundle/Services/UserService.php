<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 17/09/2016
 * Time: 14:31
 */

namespace AppBundle\Services;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserService extends Controller
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createUser(User $user){
        $this->em->persist($user);
        $this->em->flush();
    }
}
