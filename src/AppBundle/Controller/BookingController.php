<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BookingController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('booking/index.html.twig');
    }

    // Création formulaires
    /**
     * @Route("/tickets", name="tickets")
     * @Method("POST")
     */
    public function ticketsAction()
    {
        return $this->render('booking/tickets.html.twig');
    }

    // Résumé de la commande
    /**
     * @Route("/checkout", name="checkout")
     * @Method("POST")
     */
    public function checkoutAction()
    {
        return $this->render('booking/checkout.html.twig');
    }
}
