<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CartService;
use App\Service\EmailService;
use App\Repository\TicketRepository;
use App\Form\UserIdentityCartFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    private CartService $cartService;

    public function __construct(
        CartService $cartService)
    {    
        $this->cartService = $cartService;
    }
    
     /************* Affiche les produits du panier ***************/
     #[Route('/order/cart', name: 'cart')]
     public function showCart(?User $user): Response     //Permet au user de voir son panier meme déco
     {
        // Récupérer le panier depuis le service
         $cart = $this->cartService->getCart();
         $total = $this->cartService->getTotal();
         $groupedCart = $this->cartService->groupCartByExhibition($cart);

         //Obl pour commander
         $form = $this->createForm(UserIdentityCartFormType::class);
         
         return $this->render('order/cart.html.twig', [
            'groupedCart' => $groupedCart,
             'cart' => $cart,
             'total' => $total,
             'form' => $form->createView(),
         ]);
     }
     

    /************* Ajoute un ticket au panier  ***************/
    #[Route('/ticket/{exhibitionId}/addTicketToCart/{ticketId}/{origin}', name: 'addTicketToCart')]
    public function addTicketToCart(              
        TicketRepository $ticketRepo, 
        int $exhibitionId, 
        int $ticketId, 
        string $origin,
        ExhibitionShareRepository $exhibitionShareRepo): Response
    {
        // Récup du ticket via le repository
        $ticket = $ticketRepo->find($ticketId);

        // Récup de l'exposition via le repository en utilisant l'ID de la route
        $exhibition = $exhibitionShareRepo->find($exhibitionId);


        // Ajout du ticket au panier via le service
        $this->cartService->addCart($ticket, $exhibitionId);

        // Redirection en fonction de l'origine
        if ($origin === 'listExhibit') {
            return $this->redirectToRoute('exhibition', ['slug' => $exhibition->getSlug()]);
        }

        // Redirection par défaut
        return $this->redirectToRoute('cart', ['exhibition' => $exhibitionId]);
    }

/************* Soustrait un produit au panier ***************/
    #[Route('/ticket/{exhibitionId}/removeTicketFromCart/{ticketId}/{origin}', name: 'removeTicketFromCart')]
    public function removeTicketFromCart(        
        TicketRepository $ticketRepo, 
        int $exhibitionId, 
        int $ticketId, 
        string $origin,
        ExhibitionShareRepository $exhibitionShareRepo) : Response
    {
         // Récupération du ticket via le repository
         $ticket = $ticketRepo->find($ticketId);

         // Récup de l'exposition via le repository en utilisant l'ID de la route
         $exhibition = $exhibitionShareRepo->find($exhibitionId);

        // Soustraction au panier via le service
        $this->cartService->removeCart($ticket, $exhibitionId);

        if ($origin === 'listExhibit') { // Si l'origine est 'listExhibit', rediriger vers la page de ventes des tickets
            return $this->redirectToRoute('exhibition', ['slug' => $exhibition->getSlug()]);
        }

        if ($origin === 'cart') { // Si l'origine est 'cart', rediriger vers le panier
            return $this->redirectToRoute('cart', [
                'exhibition' => $exhibitionId,
            ]);
        }

        // Par défaut : rediriger vers le panier
        return $this->redirectToRoute('cart');
    }


/************* Vider le panier ***************/

    // Confirmation de suppression du panier 
    #[Route('/order/deleteCartConfirm', name: 'deleteCartConfirm')]
    public function deleteCartConfirm(): Response
    {    
        return $this->render('order/deleteCartConfirm.html.twig', [
    ]);
    }

    // Vider le panier définitivement 
    #[Route('/order/cart/delete', name: 'deleteCart')]
    public function deleteCart(): Response
    {
        $this->cartService->clearCart(); // Vide le panier

        return $this->redirectToRoute('cart'); // Redirige vers la page du panier
    }

/********************** Retirer un article (ligne) du panier *****************/
    #[Route('/order/cart/remove/{id}', name: 'removeProduct')]
    public function removeProductToCart(
        string $id): Response
    {
        $this->cartService->removeProduct($id); // Appelle la fonction pour supprimer l'élément

        return $this->redirectToRoute('cart'); 
    }

}
