<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 14/09/2016
 * Time: 21:01
 */

namespace AppBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StripeService extends Controller
{

    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function createCharge($amount, $token, $email)
    {
        \Stripe\Stripe::setApiKey($this->key);

        \Stripe\Charge::create(array(
            'amount' => $amount * 100,
            'currency' => 'EUR',
            'source' => $token, // obtained with Stripe.js
            'description' => 'Charge for '.$email
        ));
    }
}
