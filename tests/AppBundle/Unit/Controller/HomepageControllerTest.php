<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 20/09/2016
 * Time: 08:46
 */

namespace tests\AppBundle\Unit\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageControllerTest extends WebTestCase

{

    public function testIndexAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Bienvenue")')->count()
        );
    }

    public function testBilletterieLink()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/billetterie');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Réservation")')->count()
        );
    }

    public function testFormDisplay()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/billetterie');

        $this->assertGreaterThan(3, $crawler->filter('div.form-group')->count());
    }


}
