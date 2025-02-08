<?php

namespace App\Controller;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    #[Route('/ordre/payment', name: 'app_payment')]
    public function index(): Response
    {
       //die('c est bien moi, le PaymentController');
        Stripe::setApiKey('sk_test_51QinuHFTwTPYuwwqpfL8QnMtr0vdqE0Bi099H7u4CsJ5cJ3zUubCEJO3ZAHI8zAWQoYf70NQen7Q8oqzoOLHvaYB006BtAQrwC');

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        //possible d'ajouter produits via interface stripe visuel
        $checkout_session = Session::create([
            'line_items' => [[
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => '2000',
                    'product_data' => [
                        'name' => 'Test Product',
                    ]
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        //header("HTTP/1.1 303 See Other");
        //header("Location: " . $checkout_session->url);

        return $this->redirect($checkout_session->url);
    }
}
