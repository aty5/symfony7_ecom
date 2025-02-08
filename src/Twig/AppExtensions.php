<?php

namespace App\Twig;

use App\Class\Cart;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{
    private CategoryRepository $categoryRepository;
    private Cart $cart;

    public function __construct(CategoryRepository $categoryRepository,
                                Cart $cart)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cart = $cart;
    }
    public function getFilters(){

        return [
          new TwigFilter('formatedPrice', [$this, 'formatedPrice']),
        ];

    }

    public function formatedPrice($price){
        return number_format($price, 2, ',', ''). ' €';
    }

    public function getGlobals(): array
    {
        return [
            'allCategories' => $this->categoryRepository->findAll(),
            'fullCartQuantity' => $this->cart->fullQuantity(),
        ];
    }
}