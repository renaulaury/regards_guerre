<?php

namespace App\Controller;


use App\Entity\Ticket;
use App\Entity\Exhibition;
use App\Service\CartService;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
   
    //Function to me / Ajoute des produits au panier
    #[Route('/ticket/{exhibition}/addTicketToCart/{ticket}', name: 'addTicketToCart')]
    public function addTicketToCart(RequestStack $requestStack, TicketRepository $ticketRepo, Exhibition $exhibition, Ticket $ticket): Response
    {
        //Récupére la session
        $session = $requestStack->getSession();
        
        //Ajout au panier grace au service
        $ticket = $ticketRepo->addCart();

        

        return $this->redirectToRoute('ticket', [
            'exhibition' => $exhibition->getId(),   
            'ticket' => $ticket->getId(),            
        ]);
    }
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


    

