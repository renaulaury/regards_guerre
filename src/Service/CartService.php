<?php

namespace App\Service;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\Share\ExhibitionShareRepository; 

class CartService 
{
  private TicketRepository $ticketRepository; //privée car uniquement nécessaire ici
  private RequestStack $requestStack;
  private ExhibitionShareRepository $exhibitionShareRepository; 

  public function __construct(
    RequestStack $requestStack, 
    TicketRepository $ticketRepo, 
    ExhibitionShareRepository $exhibitionShareRepo) 
  {  
    $this->ticketRepository = $ticketRepo;
    $this->requestStack = $requestStack;
    $this->exhibitionShareRepository = $exhibitionShareRepo; 
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
    public function addCart(Ticket $ticket, int $exhibitionId, int $qty = 1)
{
    // Récupérer le panier depuis la session    
    $cart = $this->getCart();

    if ($ticket) {
        // Récupérer les informations via le repository
        $ticketId = $ticket->getId();
        $ticketDetails = $this->ticketRepository->findTicketDetails($ticketId);

        // Vérifier si on a bien récupéré les informations nécessaires
        if ($ticketDetails) {
            // Récup l'expo associé
            $exhibition = $this->exhibitionShareRepository->find($exhibitionId); 
            $price = $ticketDetails['price'];

            // Créer une clé unique (dans $cart) combinant exhibitionId et ticketId
            $cartKey = $exhibitionId.'_'.$ticketId;

            // Si le ticket est déjà dans le panier pour cette exposition, on met à jour la quantité
            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['qty'] += $qty;
            } else {
                // Sinon, on ajoute le ticket au panier avec ses informations
                $cart[$cartKey] = [
                    'ticket' => $ticket,
                    'ticketId' => $ticketId,
                    'exhibition' => $exhibition,
                    'exhibitionId' => $exhibitionId,
                    'qty' => $qty,
                    'price' => $price,
                ];
            }

            // Ajouter le total de la ligne 
            $cart[$cartKey]['totalLine'] = $cart[$cartKey]['price'] * $cart[$cartKey]['qty'];            
        }
    }

    // Sauvegarde du panier
    $this->setCart($cart);
    $this->updateCartTotal($cart);
}



 /************* Soustraire un ticket ****************/  
 public function removeCart(Ticket $ticket, int $exhibitionId, int $qty = 1)
{
    $cart = $this->getCart(); // Récup le contenu actuel du panier depuis la session
    $ticketId = $ticket->getId(); // Récup de l'id unique du ticket à suppr
    $cartKey = $exhibitionId.'_'.$ticketId; // Reconstruit la clé unique du ticket dans le panier en utilisant l'ID de l'expo et l'ID du ticket

    // Vérif si le ticket est présent dans le panier
    if (isset($cart[$cartKey])) {
        $cart[$cartKey]['qty'] -= $qty;

        //Suppression totale du ticket <= 0
        if ($cart[$cartKey]['qty'] <= 0) {
            unset($cart[$cartKey]);
        }
    }

    $this->setCart($cart); //Maf panier
}

   /************* Supprime le panier complet ****************/
    public function clearCart(): void
    {
        $this->getSession()->remove('cart');
    }


 /***************************** Retirer un article (ligne) du panier ***************/
    public function removeProduct(string $cartKey): void
    {
        $cart = $this->getCart();
        
        //Vérif du produit dans le panier
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]); //Suppression
        }
        
        $this->setCart($cart);
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
        // Init tableau de regroupement
        $groupedCart = [];

        foreach ($cart as $product) {
            // Récupération des IDs pour plus de lisibilité
            $exhibitionId = $product['exhibitionId'];
            $ticketId = $product['ticketId'];

            // Si expo n'existe pas on la crée
            if (!isset($groupedCart[$exhibitionId])) {
                $groupedCart[$exhibitionId] = [
                    'exhibition' => $product['exhibition'], // Add infos expo
                    'tickets' => [] // Init tabl tickets
                ];
            }

            // Si ticket n'existe pas on le crée
            if (!isset($groupedCart[$exhibitionId]['tickets'][$ticketId])) {
                $groupedCart[$exhibitionId]['tickets'][$ticketId] = [
                    'ticket' => $product['ticket'], // Add infos ticket
                    'quantity' => $product['qty'],
                    'price' => $product['price'],
                    // Ajout optionnel du total ligne si nécessaire
                    'totalLine' => $product['price'] * $product['qty']
                ];
            } else {
                // Si ticket existe on incrémente qty
                $groupedCart[$exhibitionId]['tickets'][$ticketId]['quantity'] += $product['qty'];
                // Mise à jour du total ligne si vous l'avez ajouté
                if (isset($groupedCart[$exhibitionId]['tickets'][$ticketId]['totalLine'])) {
                    $groupedCart[$exhibitionId]['tickets'][$ticketId]['totalLine'] += $product['price'] * $product['qty'];
                }
            }
        }

        return $groupedCart;
    }

}

