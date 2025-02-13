<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderController extends AbstractController

{
    /************* Affiche le récapitulatif de la commande ****************/
    #[Route('/order', name: 'index')]
    public function showCart(CartService $cartService): Response
    {
        return $this->render('order/index.html.twig', [
        ]);
    }
    

    
}
