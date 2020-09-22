<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, ProductRepository $repository)
    {
        $priceFilter = $request->query->get('price', 100);

        // Récupèrer les 4 produits plus chers qu'un certain prix
        $expensiveProducts = $repository->findAllGreatherThanPrice($priceFilter);
        // Récupèrer 4 produits de la catégorie Smartphone
        $productsFromCategory = $repository->findAllFromCategory('Smartphone');

        return $this->render('home/index.html.twig', [
            'expensive_products' => $expensiveProducts,
            'products_from_category' => $productsFromCategory,
            'price_filter' => $priceFilter,
        ]);
    }
}
