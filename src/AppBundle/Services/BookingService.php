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

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    // ÉTAPE 1 : Instancie une nouvelle commande à partir des infos du premier formulaire
    public function createCommande($day, $type, $email)
    {
        $commande = new Commande();
        $commande->setVisitDate($day);
        $commande->setType($type);
        $commande->setEmail($email);
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
        $ticket->setFirstname($firstname);
        $ticket->setLastname($lastname);
        $ticket->setCountry($country);
        $ticket->setBirthday($birthday);
        $ticket->setReduced($reduced);
        return $ticket;
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

    // Calcule la différence entre la date du jour et la date de naissance saisie
    // Persiste l'âge du visiteur en base de données
    public function getVisitorAge($ticketId)
    {
        $ticket = $this->getTicket($ticketId);
        $birthday = $ticket->getBirthday();
        $now = new \DateTime();
        $interval = $now->diff($birthday);
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
            $ticket->setPrice(0);
        }
        // Tarif enfant
        elseif ($age < 12) {
            if ($type === 'full')
            {
                $ticket->setPrice(8);
            }
            else {
                $ticket->setPrice(4);
            }

        }
        // Tarif normal
        elseif ($age < 60) {
            if (!$reduced) {
                if ($type === 'full')
                {
                    $ticket->setPrice(16);
                }
                else {
                    $ticket->setPrice(8);
                }
            }
            else if ($type === 'full') {
                $ticket->setPrice(10);
            }
            else {
                $ticket->setPrice(5);
            }

        }
        // Tarif senior
        else {
            if ($type === 'full')
            {
                $ticket->setPrice(12);
            }
            else {
                $ticket->setPrice(6);
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

}
