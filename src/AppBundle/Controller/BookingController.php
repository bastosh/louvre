<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\indexType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(indexType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            return $this->redirectToRoute('tickets');
        }
        return $this->render('booking/index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    // Création formulaires
    /**
     * @Route("/tickets", name="tickets")
     * @Method({"GET", "POST"})
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
