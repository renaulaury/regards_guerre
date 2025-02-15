<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Exhibition;
use App\Service\CartService;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    private $cartService;

    public function __construct(CartService $cartService)
  {    
    $this->cartService = $cartService;
  }

     /************* Affiche les produits du panier ***************/
     #[Route('/order/cart', name: 'cart')]
     public function showCart(CartService $cartService): Response
     {
        // Récupérer le panier depuis le service
         $cart = $cartService->getCart();
         $total = $cartService->getTotal();
 
         
         return $this->render('order/cart.html.twig', [
             'cart' => $cart,
             'total' => $total,
         ]);
     }
 

    /************* Ajoute un ticket au panier  ***************/
    #[Route('/ticket/{exhibitionId}/addTicketToCart/{ticketId}/{origin}', name: 'addTicketToCart')]
    public function addTicketToCart(CartService $cartService, TicketRepository $ticketRepo, int $exhibitionId, int $ticketId, string $origin): Response
    {
        // Récupération du ticket via le repository
        $ticket = $ticketRepo->find($ticketId);


        // Ajout du ticket au panier via le service
        $cartService->addCart($ticket);

        // Redirection en fonction de l'origine
        if ($origin === 'ticket') {
            return $this->redirectToRoute('ticket', ['exhibition' => $exhibitionId]);
        }

        if ($origin === 'cart') {
            return $this->redirectToRoute('cart', ['exhibition' => $exhibitionId]);
        }
    }

/************* Soustrait un produit au panier ***************/
    #[Route('/ticket/{exhibitionId}/removeTicketFromCart/{ticket}/{origin}', name: 'removeTicketFromCart')]
    public function removeTicketFromCart(CartService $cartService, Exhibition $exhibition, Ticket $ticket, string $origin): Response
    {
        // Soustraction au panier via le service
        $cartService->removeCart($ticket);

        if ($origin === 'ticket') { // Si l'origine est 'ticket', rediriger vers la page de ventes des tickets
            return $this->redirectToRoute('ticket', [
                'exhibition' => $exhibition->getId()
            ]);
        }

        if ($origin === 'cart') { // Si l'origine est 'cart', rediriger vers le panier
            return $this->redirectToRoute('cart', [
                'exhibition' => $exhibition->getId()
            ]);
        }

        // Redirection vers la page de l'exposition avec les tickets
        return $this->redirectToRoute('ticket', [
            'exhibition' => $exhibition->getId()
        ]);
    }

    

    /************* Vide le panier ***************/

    /* Confirmation de suppression du panier */
    #[Route('/order/deleteCartConfirm', name: 'deleteCartConfirm')]
    public function deleteCartConfirm(): Response
    {    
        return $this->render('order/deleteCartConfirm.html.twig', [
    ]);
    }

    /* Vider le panier définitivement */
    #[Route('/order/cart/delete', name: 'deleteCart')]
    public function deleteCart(CartService $cartService): Response
    {
        $cartService->clearCart(); // Vide le panier

        return $this->redirectToRoute('cart'); // Redirige vers la page du panier
    }

    /********************** Retirer un article du panier *****************/
    #[Route('/order/cart/remove/{id}', name: 'removeProduct')]
    public function removeProductToCart(CartService $cartService, int $id): Response
    {
        $cartService->removeProduct($id); // Appelle la fonction pour supprimer l'élément

        return $this->redirectToRoute('cart'); 
    }





    
}
    