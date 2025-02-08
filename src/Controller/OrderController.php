<?php

namespace App\Controller;

use App\Class\Cart;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    /*
     * 1ere etape du tunnel d achat
     * adresse + choix transporteur
     */
    #[Route('/order/delivery', name: 'app_order')]
    public function index(): Response
    {

        $addresses = $this->getUser()->getAddresses();

        if (!count($addresses)) {
            return $this->redirectToRoute('app_account_address_form');
        }

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary')
        ]);


        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }

    /*
     * 2eme etape du tunnel d achat
     * recap commande
     * insertion en bdd
     * preparation paiement STRIPE
     */
    #[Route('/order/summary', name: 'app_order_summary')] //, methods: ['POST'])]
    public function add(Request $request, Cart $cart): Response
    {
        //permet de mettre en place une redirection contrairement à l'attribut @[Route]
        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();

            //Stocker les infos en bdd

        }
        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $cart->getCart(),
            'totalWT' => $cart->getTotalWT(),
        ]);
    }
}
