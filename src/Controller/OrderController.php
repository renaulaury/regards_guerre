<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderController extends AbstractController
{
    //Affiche les produits du panier
    #[Route('/order/cart', name: 'order')]
    public function index(): Response
    {


        return $this->render('order/cart.html.twig', [
        ]);
    }

    
}
