<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Exhibition;
use App\Service\BasketService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BasketController extends AbstractController
{
    private $basketService; //privée car uniquement nécessaire ici

    public function __construct(BasketService $basketService)
    {
        $this->basketService = $basketService;
    }

    //Function to me
    // #[Route('/ticket/{exhibition}/addTicketToBasket/{ticket}', name: 'addTicketToBasket')]
    // public function addTicketToBasket(Request $request, Exhibition $exhibition, Ticket $ticket): Response
    // {

    //     //Récupére la session
    //     $session = $request->getSession();

    //     // Récupérer le panier depuis la session
    //     $basket = $session->get('basket', []);

    //     // Ajouter le ticket au panier ou augmenter la quantité
    //     if (isset($basket[$ticket->getId()])) {
    //         $basket[$ticket->getId()]++;
    //     } else {
    //         $basket[$ticket->getId()] = 1;
    //     }

    //     // Sauvegarder à nouveau dans la session
    //     $session->set('basket', $basket);

    //     return $this->redirectToRoute('ticket', [
    //         'exhibition' => $exhibition->getId(),   
    //         'ticket' => $ticket->getId(),            
    //     ]);
    // }
}
    // #[Route('/ajouter-au-panier/{id}', name: 'ajouter_panier')]
    // public function ajouterProduit(int $id): Response
    // {
    //     // Simuler la récupération d'un produit (à partir d'une base de données, par exemple)
    //     $produit = new Produit();
    //     $produit->setId($id);
    //     $produit->setNom("Produit $id");
    //     $produit->setPrix(19.99); // Simuler le prix

    //     // Ajouter le produit au panier
    //     $this->panierService->ajouterProduit($produit);

    //     return $this->redirectToRoute('panier');
    // }


    // #[Route('/panier', name: 'panier')]
    // public function afficherPanier(): Response
    // {
    //     $panier = $this->panierService->getPanier();
    //     $quantiteTotale = $this->panierService->getQuantiteTotale();
    //     $prixTotal = $this->panierService->getPrixTotal();

    //     return $this->render('panier/index.html.twig', [
    //         'panier' => $panier,
    //         'quantiteTotale' => $quantiteTotale,
    //         'prixTotal' => $prixTotal,
    //     ]);
    // }


    

