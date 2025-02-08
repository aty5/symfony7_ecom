<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{

    #[Route('/order/payment/{id_order}', name: 'app_payment')]
    public function index($id_order, OrderRepository $orderRepository): Response
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        //sécurisation url en dur en verifiant user
        $order = $orderRepository->findOneBy([
            'id' => $id_order,
            'user' => $this->getUser()
        ]);

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        $products_for_stripe = [];
        foreach ($order->getOrderDetails() as $product) {
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => number_format($product->getProductPriceWT() * 100, 0, '', ''), //format stripe
                    'product_data' => [
                        'name' => $product->getProductName(),
                        'images' => [ //non visible en env de dev
                            $_ENV['DOMAIN'] . '/uploads/' . $product->getProductIllustration()
                        ]
                    ]
                ],
                'quantity' => $product->getProductQuantity(),
            ];
        }
        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => number_format($order->getCarrierPrice() * 100, 0, 0, 0), //format stripe
                'product_data' => [
                    'name' => 'Transporteur : ' . $order->getCarrierName(),
                ]
            ],
            'quantity' => 1,
        ];


        //die('c est bien moi, le PaymentController');
        Stripe::setApiKey('sk_test_51QinuHFTwTPYuwwqpfL8QnMtr0vdqE0Bi099H7u4CsJ5cJ3zUubCEJO3ZAHI8zAWQoYf70NQen7Q8oqzoOLHvaYB006BtAQrwC');
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        //possible d'ajouter produits via interface stripe visuel mais redondant/inutile
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [[
                $products_for_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $_ENV['DOMAIN'] . '/success.html',
            'cancel_url' => $_ENV['DOMAIN'] . '/cancel.html',
        ]);

        //header("HTTP/1.1 303 See Other");
        //header("Location: " . $checkout_session->url);

        return $this->redirect($checkout_session->url);
    }
}
