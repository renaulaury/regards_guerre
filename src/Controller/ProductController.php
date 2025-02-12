<?php

namespace App\Controller;


use App\Entity\Exhibition;
use App\Repository\ProductRepository;
use App\Repository\ExhibitionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProductController extends AbstractController
{
   #[Route('/product', name: 'product')]
    public function index(ProductRepository $productRepo, ExhibitionRepository $exhibitRepo): Response
    {
        //Récup de tous les produits par exposition 
        $priceByExhibit = $productRepo->findAllProductsByExhibition();

        //Récup uniquement les infos des prochaines expos
        $exhibitions = $exhibitRepo->findAllNextExhibition();

        return $this->render('product/index.html.twig', [
            'priceByExhibit' => $priceByExhibit,
            'exhibitions' => $exhibitions,           
        ]);
}

    
}

