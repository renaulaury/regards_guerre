<?php

namespace App\Service;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService 
{
  private TicketRepository $ticketRepository; //privée car uniquement nécessaire ici
  private RequestStack $requestStack;

  public function __construct(RequestStack $requestStack, TicketRepository $ticketRepo)
  {  
    $this->ticketRepository = $ticketRepo;
    $this->requestStack = $requestStack;
  }

  private function getSession()
  {
      return $this->requestStack->getCurrentRequest()->getSession();
  }

  public function getCart(): array
  {
    return $this->getSession()->get('cart', []);

  }

  public function setCart(array $cart): void
  {
    $this->getSession()->set('cart', $cart);  
  }

    /************* Ajouter un produit au panier ****************/

    public function addCart(Ticket $ticket, int $qty = 1)
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
                        'ticketId' => $ticketId,
                        'exhibition' => $exhibition,
                        'exhibitionId' => $exhibitionId,
                        'qty' => $qty,
                        'price' => $price,
                    ];
                }

                // Ajouter le total de la ligne 
                 $cart[$ticketId]['totalLine'] = $cart[$ticketId]['price'] * $cart[$ticketId]['qty'];            
            }
    }

    // Recalculer le total du panier
    $this->updateCartTotal($cart);

    // Calculer le total du panier
    $total = $this->getTotal();

    // Sauvegarde du panier et du total dans la session
    $this->getSession()->set('cart', $cart);
    $this->getSession()->set('cartTotal', $total);
}



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
     $this->getSession()->set('cart', $cart);
 }

   /************* Supprime le panier complet ****************/
    public function clearCart(): void
    {
        $this->getSession()->remove('cart');
    }


 /***************************** Retirer un article du panier ***************/
    public function removeProduct(int $id): void
    {
        $cart = $this->getCart();
    
        if (isset($cart[$id])) {
            unset($cart[$id]); // Supprime l’élément du panier
        }
    
        $this->getSession()->set('cart', $cart); // Met à jour la session
  }



  /***************************** Calcul total du panier *******************/
  public function getTotal(): float
    {
        $cart = $this->getCart(); // Récupération du panier depuis la session
        $total = 0;

        foreach ($cart as $product) {
            $total += $product['price'] * $product['qty'];
        }

        return $total;
    }

// Fonction pour mettre à jour le total du panier
    public function updateCartTotal($cart)
    {
        $total = 0;

        foreach ($cart as $product) {
            $total += $product['totalLine']; // Total de la ligne du ticket
        }

        // Mettre à jour le total dans la session
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $session->set('cartTotal', $total);
    }
 

/************* Compteur de produit dans le panier ****************/
  public function cartCount(): int
  {
      // Récupère le panier depuis la session
      $cart = $this->getCart();

      // Additionne toutes les quantités des produits dans le panier
      return array_sum(array_column($cart, 'qty'));
  }

/************* Regroupement des achats  ****************/
  public function groupCartByExhibition(array $cart): array
    {
        //Init tabl de regroupement
        $groupedCart = [];

        foreach ($cart as $product) {
            if (!isset($groupedCart[$product['exhibitionId']])) { //si expo n'existe pas on la crée
                $groupedCart[$product['exhibitionId']] = [
                    'exhibition' => $product['exhibition'], //Add infos expo
                    'tickets' => [] //Init tabl tickets
                ];
            }

            if (!isset($groupedCart[$product['exhibitionId']]['tickets'][$product['ticketId']])) {//si ticket n'existe pas on le crée
                $groupedCart[$product['exhibitionId']]['tickets'][$product['ticketId']] = [
                    'ticket' => $product['ticket'], //Add infos ticket
                    'quantity' => $product['qty'],
                    'price' => $product['price'],
                ];
            } else { //Si ticket existe on incrémente qty
                $groupedCart[$product['exhibitionId']]['tickets'][$product['ticketId']]['quantity'] += $product['qty'];
            }
        }

        return $groupedCart;
    }

}

