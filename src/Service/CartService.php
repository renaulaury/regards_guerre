<?php

namespace App\Service;


use App\Entity\Ticket;
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
    // $this->requestStack = $requestStack;
  }

  private function getSession()
  {
    return $this->session;
  }

  public function getCart(): array
  {
    // return $this->session->get('cart', []);
    return $this->getSession()->get('cart', []);

  }

  public function setCart(array $cart): void
  {
    $this->getSession()->set('cart', $cart);  
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
                    'ticketId' => $ticketId,
                    'exhibition' => $exhibition,
                    'exhibitionId' => $exhibitionId,
                    'qty' => $qty,
                    'price' => $price,
                ];
            }
            // Ajouter le total de la ligne
            // Total de la ligne = prix du ticket en fonction de l'Id * la quantité en fonction de l'Id
            $cart[$ticketId]['totalLine'] = $cart[$ticketId]['price'] * $cart[$ticketId]['qty'];
        
        }
    }

    // Sauvegarde du panier dans la session
    $this->getSession()->set('cart', $cart);
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
 

  /************* Compteur de produit dans le panier ****************/
  public function cartCount(): int
  {
      // Récupère le panier depuis la session
      $cart = $this->getCart();

      // Additionne toutes les quantités des produits dans le panier
      return array_sum(array_column($cart, 'qty'));
  }

}