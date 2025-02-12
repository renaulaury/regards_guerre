<?php

namespace App\Service;


use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;


class CartService 
{
  private $session; //privée car uniquement nécessaire ici

  public function __construct(RequestStack $requestStack)
  {
    $this->session = $requestStack->getSession();
  }

 
     //Compteur de produit dans le panier
   public function cartCount(): int
   {
         
       // Vérifier si la session est disponible
       if ($this->session->isStarted()) {

        // Récupérer le panier depuis la session
        $cart = $this->session->get('cart', []);

        // Compter le nombre total d'articles dans le panier
        $totalCart = array_sum($cart); // Additionne toutes les quantités

        return $totalCart;
    }

    return 0; // Retourner 0 si la session n'est pas disponible
  }

  public function addCart(Product $product, int $qty = 1) 
  {  
    // Récupérer le panier depuis la session
    $cart = $this->session->get('cart', []);

    // Ajouter le produit au panier 
    if (isset($cart[$product->getId()])) {
        $cart[$product->getId()] ['qty'] += $qty;
    } else {
        // Sinon, on l'ajoute au panier
        $cart[$product->getId()] = [
          'cart' => $product,
          'qty' => $qty
      ];
    }

    // Sauvegarder à nouveau dans la session
    $this->session->set('cart', $cart);
  }
}


// Ajouter un produit au panier
// public function ajouterProduit(Produit $produit, int $quantite = 1)
// {
//     $panier = $this->session->get('panier', []);

//     if (isset($panier[$produit->getId()])) {
//         // Si le produit est déjà dans le panier, on met à jour la quantité
//         $panier[$produit->getId()]['quantite'] += $quantite;
//     } else {
//         // Sinon, on l'ajoute au panier
//         $panier[$produit->getId()] = [
//             'produit' => $produit,
//             'quantite' => $quantite
//         ];
//     }

//     // Sauvegarde du panier dans la session
//     $this->session->set('panier', $panier);
// }

// Récupérer tous les produits du panier
// public function getPanier(): array
// {
//     return $this->session->get('panier', []);
// }

// // Calculer le nombre total d'articles dans le panier
// public function getQuantiteTotale(): int
// {
//     $panier = $this->getPanier();
//     $quantiteTotale = 0;

//     foreach ($panier as $item) {
//         $quantiteTotale += $item['quantite'];
//     }

//     return $quantiteTotale;
// }

// // Calculer le prix total du panier
// public function getPrixTotal(): float
// {
//     $panier = $this->getPanier();
//     $prixTotal = 0;

//     foreach ($panier as $item) {
//         $prixTotal += $item['produit']->getPrix() * $item['quantite'];
//     }

//     return $prixTotal;
// }

// // Retirer un produit du panier
// public function retirerProduit(Produit $produit)
// {
//     $panier = $this->session->get('panier', []);

//     if (isset($panier[$produit->getId()])) {
//         unset($panier[$produit->getId()]);
//         $this->session->set('panier', $panier);
//     }
// }

// // Vider le panier
// public function viderPanier()
// {
//     $this->session->remove('panier');
// }
