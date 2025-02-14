<?php

namespace App\Service;


use App\Entity\Ticket;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class CartService 
{
  private $session; //privée car uniquement nécessaire ici
  private $orderRepository;

  public function __construct(RequestStack $requestStack, OrderRepository $orderRepo)
  {
    $this->session = $requestStack->getSession();
    $this->orderRepository = $orderRepo;
  }

  public function getCart(): array
  {
    return $this->session->get('cart', []);
  }

  public function setCart(array $cart): void
  {
    $this->session->get('cart', $cart);
  }


    /************* Ajouter un produit au panier ****************/
  
    public function addCart(Ticket $ticket = null, int $qty = 1)
    {
        // Récupérer le panier depuis la session
        $cart = $this->getCart();

        if ($ticket) {
            $ticketId = $ticket->getId();
            

            // Si le ticket est déjà dans le panier, on augmente la quantité, sinon on l'ajoute
            if (isset($cart[$ticketId])) {
                $cart[$ticketId]['qty'] += $qty;
            } else {
                $cart[$ticketId] = [
                    'ticket' => $ticket,
                    'qty' => $qty,
                ];
            }
        }       
            // Enregistrement des changements dans la session
            $this->session->set('cart', $cart);
    }

 
// if ($ticket) {
    //   $ticketId = $ticket->getId();
    //   $ticketPricings = $ticket->getTicketPricings();

    //   if (isset($cart[$ticketId])) {
    //       $cart[$ticketId]['qty'] += $qty;
    //   } else {
    //       // Sinon, on l'ajoute au panier
    //       $cart[$ticketId] = [
    //           'ticket' => $ticket,
    //           'qty' => $qty,
    //           'ticketPricings' => $ticketPricings,
    //       ];
    //   }
    // }
    //Ici le même code adapter pour l'ajout de goodies au panier plus tard
    
 


 /************* Soustraire un produit ****************/
  
 public function removeCart(Ticket $ticket, int $qty = 1)
 {
     // Récupérer le panier depuis la session
     $cart = $this->getCart();
 
     if ($ticket) {
         $ticketId = $ticket->getId();
 
         // Vérifier si le ticket est déjà dans le panier
         if (isset($cart[$ticketId])) {
             // Réduire la quantité
             $cart[$ticketId]['qty'] -= $qty;
 
             // Si la quantité devient 0 ou moins, on supprime l'entrée du panier
             if ($cart[$ticketId]['qty'] <= 0) {
                 unset($cart[$ticketId]);
             }
         }
     }
 
     // Sauvegarde dans la session
     $this->session->set('cart', $cart);
 }


   /************* Supprime le panier complet ****************/
    public function clearCart(): void
    {
        $this->session->remove('cart');
    }

 

 /***************************** Retirer un article du panier ***************/
    public function removeProduct(int $id): void
    {
        $cart = $this->getCart();
    
        if (isset($cart[$id])) {
            unset($cart[$id]); // Supprime l’élément du panier
        }
    
        $this->session->set('cart', $cart); // Met à jour la session
  }

  

  //    /************* Compteur de produit dans le panier ****************/
  public function cartCount(): int
  {
      // Récupère le panier depuis la session
      $cart = $this->getCart();

      // Additionne toutes les quantités des produits dans le panier
      return array_sum(array_column($cart, 'qty'));
  }
}
  












