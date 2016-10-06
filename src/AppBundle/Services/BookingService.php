<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 13/09/2016
 * Time: 22:20
 */

namespace AppBundle\Services;

use AppBundle\Entity\Commande;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BookingService extends Controller
{
    protected $em;
    protected $gratuit;
    protected $enfant;
    protected $reduit;
    protected $normal;
    protected $senior;

    public function __construct(EntityManager $em, $gratuit, $enfant, $reduit, $normal, $senior)
    {
        $this->em = $em;
        $this->gratuit = $gratuit;
        $this->enfant = $enfant;
        $this->reduit = $reduit;
        $this->normal = $normal;
        $this->senior = $senior;

    }

    // ÉTAPE 1 : Instancie une nouvelle commande à partir des infos du premier formulaire
    public function createCommande($session, $day, $type, $email)
    {
        $today = new \DateTime('today');
        $hour = date('H');
        if ($day == $today AND $type === 'full' AND $hour > 14) {
            return false;
        }
        $commande = new Commande();
        $commande->setSession($session);
        $commande->setVisitDate($day);
        $commande->setType($type);
        $commande->setEmail($email);
        $this->saveCommande($commande);
        $this->setToken($commande->getId());
        return $commande;
    }

    // Persiste la commande en base de données
    public function saveCommande($commande)
    {
        $this->em->persist($commande);
        $this->em->flush();
    }

    // Attribue un numéro de commande unique (date + id, au format 160912_15)
    public function setToken($id)
    {
        $commande = $this->getCommande($id);
        $date = date('ymd');
        $commande->setToken($date.'_'.$id);
        $this->saveCommande($commande);
    }

    // Récupère une commande par son id
    public function getCommande($id)
    {
        $repository = $this->em->getRepository('AppBundle:Commande');
        return $repository->find($id);
    }

    // ÉTAPE 2 : Instancie un ticket pour chaque formulaire rempli
    public function createTicket($id, $firstname, $lastname, $country, $birthday, $reduced)
    {
        $ticket = new Ticket();
        $ticket->setCommande($this->getCommande($id));
        $ticket->setVisitDate($this->getCommande($id)->getVisitDate());
        $ticket->setFirstname($firstname);
        $ticket->setLastname($lastname);
        $ticket->setCountry($country);
        $ticket->setBirthday($birthday);
        $ticket->setReduced($reduced);
        $this->saveTicket($ticket);
        $ticketId = $ticket->getId();
        $this->getVisitorAge($ticketId);
        $this->getPrice($ticketId);
    }

    // Persiste le ticket en base de données
    public function saveTicket($ticket)
    {
        $this->em->persist($ticket);
        $this->em->flush();
    }

    // Récupère un ticket par son id
    public function getTicket($ticketId)
    {
        $repository = $this->em->getRepository('AppBundle:Ticket');
        return $repository->find($ticketId);
    }

    // Supprime un ticket de la base de données
    public function removeTicket($ticketId)
    {
        $ticket = $this->getTicket($ticketId);
        $this->em->remove($ticket);
        $this->em->flush();
    }

    // Calcule la différence entre la date de la visite et la date de naissance saisie
    // Persiste l'âge du visiteur en base de données
    public function getVisitorAge($ticketId)
    {
        $ticket = $this->getTicket($ticketId);
        $birthday = $ticket->getBirthday();
        $visit = $ticket->getVisitDate();
        $interval = $visit->diff($birthday);
        $age = $interval->y;
        $ticket->setAge($age);
        $this->saveTicket($ticket);
    }

    // Récupère l'âge du visiteur et attribue le tarif
    // Persiste le prix du billet en base de données
    public function getPrice($ticketId)
    {
        $ticket = $this->getTicket($ticketId);
        $age = $ticket->getAge();
        $id = $ticket->getCommande();
        $reduced = $ticket->isReduced();
        $commande = $this->getCommande($id);
        $type = $commande->getType();

        // Tarif gratuit
        if ($age < 4) {
            $ticket->setPrice($this->gratuit);
        }
        // Tarif enfant
        elseif ($age < 12) {
            if ($type === 'full')
            {
                $ticket->setPrice($this->enfant);
            }
            else {
                $ticket->setPrice($this->enfant/2);
            }

        }
        // Tarif normal
        elseif ($age < 60) {
            if (!$reduced) {
                if ($type === 'full')
                {
                    $ticket->setPrice($this->normal);
                }
                else {
                    $ticket->setPrice($this->normal/2);
                }
            }
            else if ($type === 'full') {
                $ticket->setPrice($this->reduit);
            }
            else {
                $ticket->setPrice($this->reduit/2);
            }

        }
        // Tarif senior
        else {
            if ($type === 'full')
            {
                $ticket->setPrice($this->senior);
            }
            else {
                $ticket->setPrice($this->senior/2);
            }
        }
        $this->saveTicket($ticket);
    }

    // Récupère le tarif de tous les tickets de la commande
    // Aditionne et persiste le montant en base de données
    public function getAmount($id)
    {
        $commande = $this->getCommande($id);
        $amount = 0;
        foreach ($commande->getTickets() as $ticket) {
            $amount += $ticket->getPrice();
        }
        $commande->setAmount($amount);
        $this->saveCommande($commande);
        return $amount;
    }

    // Retourne le nombre de billets réservés pour une date donnée (depuis le datepicker JS)
    public function getNumberTickets($day)
    {
        $str_date = $day;
        $obj_date = \DateTime::createFromFormat('d-m-yy', $str_date);
        $repository = $this->em->getRepository('AppBundle:Ticket');
        $tickets = $repository->findBy(array('visitDate' => $obj_date, 'status' => true));
        $quantity = 0;
        foreach ($tickets as $ticket) {
            $quantity++;
        }
        return $quantity;
    }

    // Retourne le nombre de billets réservés pour une date donnée
    public function getNbrTickets($day)
    {
        $repository = $this->em->getRepository('AppBundle:Ticket');
        $tickets = $repository->findBy(array('visitDate' => $day, 'status' => true));
        $quantity = 0;
        foreach ($tickets as $ticket) {
            $quantity++;
        }
        return $quantity;
    }

    // Retourne le nombre de billets d'une commande donnée
    public function ticketsCommande($id)
    {
        $commande = $this->getCommande($id);
        $quantity = 0;
        foreach ($commande->getTickets() as $ticket) {
            $quantity++;
        }
        return $quantity;
    }

    // Marque les billets comme réservés une fois le paiement effectué
    public function changeStatus($id)
    {
        $commande = $this->getCommande($id);
        foreach ($commande->getTickets() as $ticket)
        {
            $ticket->setStatus(true);
            $this->saveTicket($ticket);
        }
    }

    public function getSession($id)
    {
        $commande = $this->getCommande($id);
        return $commande->getSession();
    }
}
