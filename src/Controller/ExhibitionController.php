<?php

namespace App\Controller;


use App\Entity\Exhibition;
use App\Service\CartService;
use App\Repository\TicketRepository;
use App\Repository\TicketPricingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExhibitionController extends AbstractController
{
    /*Affiche la fiche complète de l'expo*/
    #[Route('/exhibition/{slug}', name: 'exhibition')]
    public function index(
        #[MapEntity(mapping: ['slug' => 'slug'])] ?Exhibition $exhibition = null,
        CartService $cartService, 
        TicketPricingRepository $ticketPricingRepository,): Response 
    {       
        // Récupérer le panier depuis la session
        $cart = $cartService->getCart();   

        // Récupérer les prix des tickets pour cette exposition spécifique
        $ticketPricings = $ticketPricingRepository->findBy(['exhibition' => $exhibition]);

        
        return $this->render('/exhibition/index.html.twig', [
            'ticketPricings' => $ticketPricings,
            'exhibition' => $exhibition,
            'cart' => $cart, 
        ]);
    }
}

 
