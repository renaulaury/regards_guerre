<?php

namespace App\Service;


use App\Entity\Ticket;
use App\Entity\TicketPricing;
use Symfony\Component\HttpFoundation\RequestStack;


class CartService 
{
  private $session; //privée car uniquement nécessaire ici

  public function __construct(RequestStack $requestStack)
  {
    $this->session = $requestStack->getCurrentRequest()->getSession();
  }

  public function getCart(): array
  {
    return $this->session->get('cart', []);
  }

  public function setCart(): array
  {
    return $this->session->get('cart', []);
  }


    /************* Ajouter un produit au panier ****************/
  
    public function addCart(Ticket $ticket = null, int $qty = 1)
{
    // Récupérer le panier depuis la session
    $cart = $this->getCart();

    if ($ticket) {
        // Récupérer les informations via le repository
        $ticketId = $ticket->getId();
        
        // Récupération de l'exposition associée
        $exhibition = $ticket->getTicketPricings()->first()->getExhibition()->getTitleExhibit();

        // Récupérer le prix standard
        $price = $ticket->getTicketPricings()->first()->getStandardPrice();

        // Si l'exposition et le prix sont disponibles, ajoutez-les au panier
        if ($exhibition && $price) {
            // Si le ticket est déjà dans le panier, on met à jour la quantité
            if (isset($cart[$ticketId])) {
                $cart[$ticketId]['qty'] += $qty;
            } else {
                // Sinon, on ajoute le ticket au panier avec ses informations
                $cart[$ticketId] = [
                    'ticket' => $ticket,
                    'exhibition' => $exhibition,
                    'qty' => $qty,
                    'price' => $price,
                ];
            }
        }
    }
    
    // Stocker le panier dans la session
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
  
















    /************************************************************************* */









