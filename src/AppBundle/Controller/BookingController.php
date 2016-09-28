<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\CommandeType;
use AppBundle\Form\Type\IndexType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Class BookingController
 * @package AppBundle\Controller
 */
class BookingController extends Controller
{
    /**
     * @Route("/billetterie", name="billetterie")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(IndexType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session = $request->cookies->get('louvre');
            $data = $form->getData();
            $commande = $this->get('booking.service')->createCommande($session, $data['day'], $data['type'], $data['email']);
            return $this->redirectToRoute('order', array(
                'id' => $commande->getId(),
            ));
        }
        return $this->render('booking/index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/order/{id}", name="order")
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function orderAction(Request $request, $id)
    {
        if ($this->get('booking.service')->getSession($id) === $request->cookies->get('louvre'))
        {
            $form = $this->createForm(CommandeType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $visitors = $data['visitors'];
                foreach ($visitors as $visitor) {
                    $this->get('booking.service')->createTicket($id, $visitor['firstname'], $visitor['lastname'], $visitor['country'], $visitor['birthday'], $visitor['reduced']);
                }
                return $this->redirectToRoute('checkout', array (
                    'id' => $id
                ));
            }
            return $this->render('booking/order.html.twig', array(
                'form' => $form->createView(),
            ));
        }
        throw new AccessDeniedException("Vous n'avez pas la permission d'accéder à cette page.");
    }

    /**
     * @param $id
     * @param Request $request
     * @Route("/checkout/{id}", name="checkout", schemes={"%secure_channel%"})
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function checkoutAction(Request $request, $id)
    {
        if ($this->get('booking.service')->getSession($id) === $request->cookies->get('louvre')) {
            $commande = $this->get('booking.service')->getCommande($id);
            $token = $request->request->get('stripeToken');
            if ($request->isMethod('POST')) {
                $this->get('stripe.service')->createCharge($commande->getAmount(), $token, $commande->getEmail());
                $this->get('booking.service')->changeStatus($commande->getId());
                $this->addFlash('success', 'Merci pour votre commande, vous allez recevoir les billets par e-mail.');
                $message = \Swift_Message::newInstance()
                    ->setSubject('Votre réservation pour le musée du Louvre')
                    ->setFrom('reservation@louvre.com')
                    ->setTo($commande->getEmail())
                    ->setBody(
                        $this->renderView(
                            'email/reservation.html.twig',
                            array(
                                'commande' => $commande,
                                'tickets' => $commande->getTickets()
                            )
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
                $response = new Response();
                $response->headers->clearCookie('louvre');
                $response->send();
                return $this->redirectToRoute('index');
            }
            if ($this->get('booking.service')->getNbrTickets($commande->getVisitDate()) + $this->get('booking.service')->ticketsCommande($id) <= 1000) {
                return $this->render('booking/checkout.html.twig', array(
                    'id' => $id,
                    'tickets' => $commande->getTickets(),
                    'visit' => $commande->getVisitDate(),
                    'email' => $commande->getEmail(),
                    'amount' => $this->get('booking.service')->getAmount($id),
                    'stripe_public_key' => $this->getParameter('stripe_public_key'),
                ));
            }
            throw new ConflictHttpException("Il n'y a plus suffisamment de places disponibles pour le jour choisi, nous ne pouvons traiter votre commande.");
        }
        throw new AccessDeniedException("Vous n'avez pas la permission d'accéder à cette page.");
    }

    /**
     * @param $ticketId
     * @param $id
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
}
