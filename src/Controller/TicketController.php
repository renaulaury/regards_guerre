<?php

namespace App\Controller;


use App\Service\CartService;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TicketController extends AbstractController
{
   #[Route('/ticket', name: 'ticket')]
    public function index(
        CartService $cartService, 
        TicketRepository $ticketRepo, 
        ExhibitionShareRepository $exhibitShareRepo): Response
    {
        // Récupérer le panier depuis la session
        $cart = $cartService->getCart();   

        //Récup de tous les produits par exposition 
        $priceByExhibit = $ticketRepo->findAllTicketsByExhibition();

        //Récup uniquement les infos des prochaines expos
        $exhibitions = $exhibitShareRepo->findAllNextExhibition();


        return $this->render('ticket/index.html.twig', [
            'priceByExhibit' => $priceByExhibit,
            'exhibitions' => $exhibitions, 
            'cart' => $cart,          
        ]);
    }
}