<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('homepage.html.twig');
    }
}
