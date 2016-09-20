<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 20/09/2016
 * Time: 09:07
 */

namespace AppBundle\Unit\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{

    public function testOrderAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/order/1');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Ajouter un visiteur")')->count()
        );
    }

    public function testCheckoutAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/checkout/1');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Ajouter un autre billet")')->count()
        );
    }
}