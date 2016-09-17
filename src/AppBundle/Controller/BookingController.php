<?php

namespace AppBundle\Controller;


use AppBundle\Form\Type\CommandeType;
use AppBundle\Form\Type\IndexType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BookingController
 * @package AppBundle\Controller
 */
class BookingController extends Controller
{
    /**
     * @Route("/billeterie", name="billetterie")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(IndexType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $commande = $this->get('booking.service')->createCommande($data['day'], $data['type'], $data['email']);
            $this->get('booking.service')->saveCommande($commande);
            $this->get('booking.service')->setToken($commande->getId());
            return $this->redirectToRoute('order', array(
                'id' => $commande->getId(),
            ));
        }
        return $this->render('booking/index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/order/{id}", name="order")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function orderAction(Request $request, $id)
    {
        $form = $this->createForm(CommandeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $visitors = $data['visitors'];
            foreach ($visitors as $visitor) {
                $ticket = $this->get('booking.service')->createTicket($id, $visitor['firstname'], $visitor['lastname'], $visitor['country'], $visitor['birthday'], $visitor['reduced']);
                $this->get('booking.service')->saveTicket($ticket);
                $this->get('booking.service')->getVisitorAge($ticket->getId());
                $this->get('booking.service')->getPrice($ticket->getId());
            }
            return $this->redirectToRoute('checkout', array (
                'id' => $id
            ));
        }
        return $this->render('booking/order.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     * @Route("/checkout/{id}", name="checkout")
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function checkoutAction(Request $request, $id)
    {

        $commande = $this->get('booking.service')->getCommande($id);
        $token = $request->request->get('stripeToken');
        if ($request->isMethod('POST')) {
            \Stripe\Stripe::setApiKey('sk_test_fjA7u1npiGjqQE8PG5BpxX01');

            \Stripe\Charge::create(array(
                'amount' => $commande->getAmount() * 100,
                'currency' => 'EUR',
                'source' => $token, // obtained with Stripe.js
                'description' => 'Charge for '.$commande->getEmail()
            ));
            $this->addFlash('success', 'Merci pour votre commande, vous allez recevoir les billets par e-mail.');
            return $this->redirectToRoute('index');
        }

        if ($this->get('booking.service')->getNbrTickets($commande->getVisitDate()) + $this->get('booking.service')->ticketsCommande($id) <= 1000) {
            return $this->render('booking/checkout.html.twig', array(
                'id' => $id,
                'tickets' => $commande->getTickets(),
                'visit' => $commande->getVisitDate(),
                'email' => $commande->getEmail(),
                'amount' => $this->get('booking.service')->getAmount($id)
            ));
        }
        throw new \Exception("Il n'y a pas suffisamment de places disponibles pour le jour choisi. Merci de modifier votre commande.");
    }

    /**
     * @param $ticketId
     * @Route("/remove/{id}/{ticketId}", name="remove")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAction($ticketId, $id) {
        $this->get('booking.service')->removeTicket($ticketId);
        return $this->redirectToRoute('checkout', array (
            'id' => $id
        ));
    }

    /**
     * @Route("/ajax", name="ajax")
     * @Method("GET")
     */
    public function ajaxAction(Request $request)
    {
        $day = $request->query->get('day');
        $tickets = $this->get('booking.service')->getNumberTickets($day);
        if ($request->isXmlHttpRequest()) {
            return new Response(1000-$tickets);
        }
        return new Response('This is not ajax!', 400);
    }
}
