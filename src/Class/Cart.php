<?php

namespace App\Class;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function add(Product $product): void
    {
        //$session = $this->requestStack->getSession();
        //dd($session);

        $cart = $this->getCart();

        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => $cart[$product->getId()]['quantity'] + 1,
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => 1,
            ];
        }

        $this->requestStack->getSession()->set('cart', $cart);

        //dd($this->requestStack->getSession()->get('cart'));
    }

    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }

    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }

    /**
     * @param $id
     * @return void
     */
    public function decrease($id): void
    {
        $cart = $this->getCart();

        if ($cart[$id]['quantity'] > 1) {
            $cart[$id]['quantity']--;
        } else {
            unset($cart[$id]);
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function fullQuantity()
    {
        $cart = $this->getCart();
        $quantity = 0;

        if (!isset($cart)) {
            return $quantity;
        }
        foreach ($cart as $product) {
            $quantity += $product['quantity'];
        }
        return $quantity;
    }

    public function getTotalWT()
    {
        $cart = $this->getCart();
        $price = 0;

        if (!isset($cart)) {
            return $price;
        }
        foreach ($cart as $product) {
            $price += ($product['object']->getPriceWT() * $product['quantity']);
        }
        return $price;
    }
}