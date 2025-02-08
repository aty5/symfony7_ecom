<?php

namespace App\Controller;

use App\Class\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
            'totalWT' => $cart->getTotalWT(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id,
                        Cart $cart,
                        ProductRepository $productRepository,
                        Request $request): Response
    {
        //dd($request->headers->get('referer'));
        $product = $productRepository->findOneById($id);

        $cart->add($product);

        $this->addFlash(
            'success',
            'Produit ajouté au panier !'
        );
        //dd($id);
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id,
                        Cart $cart,): Response
    {
        //dd($request->headers->get('referer'));

        $cart->decrease($id);

        $this->addFlash(
            'success',
            'Produit supprimé de votre panier !'
        );
        //dd($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        //dd($id);
        return $this->redirectToRoute('app_home', [
        ]);
    }
}
