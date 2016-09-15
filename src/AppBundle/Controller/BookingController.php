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
     * @Route("/", name="index")
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
                $ticketId = $ticket->getId();
                $this->get('booking.service')->getVisitorAge($ticketId);
                $this->get('booking.service')->getPrice($ticketId);
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
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function checkoutAction($id)
    {
        $commande = $this->get('booking.service')->getCommande($id);
        $ticketsJour = $this->get('booking.service')->getNbrTickets($commande->getVisitDate());
        $ticketsCommande = $this->get('booking.service')->ticketsCommande($id);
        $visit = $commande->getVisitDate();
        $tickets = $commande->getTickets();
        $amount = $this->get('booking.service')->getAmount($id);
        if ($ticketsJour + $ticketsCommande <= 1000) {
            return $this->render('booking/checkout.html.twig', array(
                'id' => $id,
                'tickets' => $tickets,
                'visit' => $visit,
                'amount' => $amount
            ));
        }
        throw new \Exception("Il n'y a pas suffisamment de places disponibles pour le jour choisi. Merci de modifier votre commande");
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
