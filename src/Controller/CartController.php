<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Exhibition;
use App\Repository\TicketRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        return $this->render('order/cart.html.twig', [
            'cart' => $cart,
    ]);
    }

    /************* Ajoute un ticket au panier ***************/
    #[Route('/ticket/{exhibition}/addTicketToCart/{ticket}', name: 'addTicketToCart')]
    public function addTicketToCart(CartService $cartService, Exhibition $exhibition, Ticket $ticket): Response
    {
        // Ajout au panier via le service
        $cartService->addCart($ticket);

        // Redirection vers la page de l'exposition avec les tickets
        return $this->redirectToRoute('ticket', [
            'exhibition' => $exhibition->getId()
        ]);
    }

    /************* Retire un ticket au panier ***************/
    #[Route('/ticket/{exhibition}/removeTicketToCart/{ticket}', name: 'removeTicketToCart')]
    public function removeTicketToCart(CartService $cartService, Exhibition $exhibition, Ticket $ticket): Response
    {
        // Ajout au panier via le service
        $cartService->removeCart($ticket);

        // Redirection vers la page de l'exposition avec les tickets
        return $this->redirectToRoute('ticket', [
            'exhibition' => $exhibition->getId()
        ]);
    }

    /************* Vide le panier ***************/
    #[Route('/order/deleteCartConfirm', name: 'deleteCartConfirm')]
    public function deleteCartConfirm(): Response
    {

        return $this->render('order/deleteCartConfirm.html.twig', [
    ]);
    }


    #[Route('/order/cart/delete', name: 'deleteCart')]
    public function deleteCart(CartService $cartService): Response
    {
        $cartService->clearCart(); // Vide le panier

        return $this->redirectToRoute('cart'); // Redirige vers la page du panier
    }


    
}
    