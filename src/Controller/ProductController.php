<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/produit/{slug}', name: 'app_product')]
    public function index($slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }

    /*
    // automapping #[MapEntity()], autre facon de faire
    #[Route('/produit/{slug}', name: 'app_product')]
    public function index(#[MapEntity(slug: 'slug')] Product $product): Response
    {
        if (!$product) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
    */

    /*
        //symfony docs
        //avec id, possible de raccourcir le code ainsi:
         #[Route('/product/{id}')]
         public function show(Product $product): Response
         {
             // use the Product!
             // ...
         }
    */

}
