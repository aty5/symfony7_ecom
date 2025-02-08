<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


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
            'success_url' => $_ENV['DOMAIN'] . '/order/payment/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['DOMAIN'] . '/my-cart/cancel',
        ]);

        //header("HTTP/1.1 303 See Other");
        //header("Location: " . $checkout_session->url);

        $order->setStripeSessionId($checkout_session->id);
        $this->entityManager->flush();
        return $this->redirect($checkout_session->url);
    }

    #[Route('/order/payment/success/{stripe_session_id}', name: 'app_payment_success')]
    public function success($stripe_session_id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findOneBy([
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser()

        ]);
        if (!$order) {
            return $this->redirectToRoute('app_home');
        }
        if ($order->getState() == 1) {
            $order->setState(2);
        }

        $this->entityManager->flush();

        return $this->render('payment/success.html.twig', [
            'order' => $order,
        ]);
    }
}
