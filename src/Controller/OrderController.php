<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        ]);

        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }
}
