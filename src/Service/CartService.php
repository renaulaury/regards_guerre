<?php

namespace App\Service;


use App\Entity\Ticket;
use App\Entity\TicketPricing;
use Symfony\Component\HttpFoundation\RequestStack;


class CartService 
{
  private $session; //privée car uniquement nécessaire ici
  private $orderRepository;

  public function __construct(RequestStack $requestStack)
  {
    $this->session = $requestStack->getSession();
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
    
 
 /************* Retirer un produit au panier ****************/
  
 public function removeCart(Ticket $ticket = null, int $qty = 1)
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
                 $cart[$ticketId]['qty'] -= $qty;
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


   /************* Supprime le panier complet ****************/
    public function clearCart(): void
    {
        $this->session->remove('cart');
    }

 }
















    /************************************************************************* */






//    /************* Compteur de produit dans le panier ****************/
//    public function cartCount(): int
//    {
         
//        // Vérifier si la session est disponible
//        if ($this->session->isStarted()) {

//         // Récupérer le panier depuis la session
//         $cart = $this->session->get('cart', []);

//         // Compter le nombre total d'articles dans le panier
//         $totalCart = array_sum($cart); // Additionne toutes les quantités

//         return $totalCart;
//     }

//     return 0; // Retourner 0 si la session n'est pas disponible
//   }
// }


