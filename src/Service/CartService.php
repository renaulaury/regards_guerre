<?php

namespace App\Service;


use App\Entity\Ticket;
use App\Repository\OrderRepository;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class CartService 
{
  private $session; //privée car uniquement nécessaire ici
  private TicketRepository $ticketRepository;

  public function __construct(RequestStack $requestStack, TicketRepository $ticketRepo)
  {
    $this->session = $requestStack->getSession();
    
    $this->ticketRepository = $ticketRepo;
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
        // Récupérer les informations via le repository
        $ticketId = $ticket->getId();
        $ticketDetails = $this->ticketRepository->findTicketDetails($ticketId);

        // Vérifier si on a bien récupéré les informations nécessaires
        if ($ticketDetails) {
            $exhibition = $ticketDetails['exhibition'];
            $exhibitionId = $ticketDetails['exhibitionId'];
            $price = $ticketDetails['price'];

            // Si le ticket est déjà dans le panier, on met à jour la quantité
            if (isset($cart[$ticketId])) {
                $cart[$ticketId]['qty'] += $qty;
            } else {
                // Sinon, on ajoute le ticket au panier avec ses informations
                $cart[$ticketId] = [
                    'ticket' => $ticket,
                    'exhibition' => $exhibition,
                    'exhibitionId' => $exhibitionId,
                    'qty' => $qty,
                    'price' => $price,
                ];
            }
        }
    }

    // Sauvegarde du panier dans la session
    $this->session->set('cart', $cart);
}


  
//     public function addCart(Ticket $ticket = null, int $qty = 1)
// {
//     $cart = $this->getCart();

//     if ($ticket) {
//         $ticketId = $ticket->getId();

//         // Appel au repository pour récupérer les infos du ticket
//         $ticketDetails = $this->orderRepository->showInfosCart();

//         // Recherche des informations du ticket spécifique
//         $ticketInfo = null;
//         foreach ($ticketDetails as $detail) {
//             if ($detail['id'] === $ticketId) {
//                 $ticketInfo = $detail;
//                 break;
//             }
//         }

//         if ($ticketInfo) {
//             $exhibition = $ticketInfo['titleExhibit'];
//             $price = $ticketInfo['standardPrice'];

//             if (isset($cart[$ticketId])) {
//                 $cart[$ticketId]['qty'] += $qty;
//             } else {
//                 $cart[$ticketId] = [
//                     'ticket' => $ticket,
//                     'exhibition' => $exhibition,
//                     'qty' => $qty,
//                     'price' => $price,
//                 ];
//             }
//         }
//     }

//     $this->session->set('cart', $cart);
// }


 
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
  












