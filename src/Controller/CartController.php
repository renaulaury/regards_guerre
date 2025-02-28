<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Entity\Exhibition;
use App\Entity\OrderDetail;
use App\Service\CartService;
use App\Service\EmailService;
use App\Repository\TicketRepository;
use App\Repository\ExhibitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
         $total = $cartService->getTotal();
 
         
         return $this->render('order/cart.html.twig', [
             'cart' => $cart,
             'total' => $total,
         ]);
     }
 

    /************* Ajoute un ticket au panier  ***************/
    #[Route('/ticket/{exhibitionId}/addTicketToCart/{ticketId}/{origin}', name: 'addTicketToCart')]
    public function addTicketToCart(CartService $cartService, TicketRepository $ticketRepo, int $exhibitionId, int $ticketId, string $origin): Response
    {
        // Récupération du ticket via le repository
        $ticket = $ticketRepo->find($ticketId);


        // Ajout du ticket au panier via le service
        $cartService->addCart($ticket);

        // Redirection en fonction de l'origine
        if ($origin === 'ticket') {
            return $this->redirectToRoute('ticket', ['exhibition' => $exhibitionId]);
        }

        if ($origin === 'cart') {
            return $this->redirectToRoute('cart', ['exhibition' => $exhibitionId]);
        }
    }

/************* Soustrait un produit au panier ***************/
    #[Route('/ticket/{exhibitionId}/removeTicketFromCart/{ticketId}/{origin}', name: 'removeTicketFromCart')]
    public function removeTicketFromCart(CartService $cartService, TicketRepository $ticketRepo, int $exhibitionId, int $ticketId, string $origin): Response
    {

         // Récupération du ticket via le repository
         $ticket = $ticketRepo->find($ticketId);

        // Soustraction au panier via le service
        $cartService->removeCart($ticket);

        if ($origin === 'ticket') { // Si l'origine est 'ticket', rediriger vers la page de ventes des tickets
            return $this->redirectToRoute('ticket', [
                'exhibition' => $exhibitionId
            ]);
        }

        if ($origin === 'cart') { // Si l'origine est 'cart', rediriger vers le panier
            return $this->redirectToRoute('cart', [
                'exhibition' => $exhibitionId
            ]);
        }
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
    public function deleteCart(CartService $cartService): Response
    {
        $cartService->clearCart(); // Vide le panier

        return $this->redirectToRoute('cart'); // Redirige vers la page du panier
    }

/********************** Retirer un article du panier *****************/
    #[Route('/order/cart/remove/{id}', name: 'removeProduct')]
    public function removeProductToCart(CartService $cartService, int $id): Response
    {
        $cartService->removeProduct($id); // Appelle la fonction pour supprimer l'élément

        return $this->redirectToRoute('cart'); 
    }


/********************** Valider la commande *****************/
    #[Route('/order/cart/orderValidated/{id}', name: 'orderValidated')]
    public function orderValidated(CartService $cartService, EntityManagerInterface $entityManager, ExhibitionRepository $exhibitRepo, TicketRepository $ticketRepo, EmailService $emailService): Response
    {
        $cart = $cartService->getCart();
        
        // Création d'une nouvelle commande
        $order = new Order();
        $order->setOrderDateCreation(new \DateTimeImmutable()); //Avec une date immuable (const)
        $order->setUser($this->getUser());

        $order->setOrderStatus('Envoyé'); 
        
        foreach ($cart as $item) {
            $orderDetail = new OrderDetail();
            $orderDetail->setOrder($order);
            
            // Charger l'objet Exhibition à partir de l'ID
            $exhibition = $exhibitRepo->find($item['exhibitionId']); 
            if ($exhibition) {
                $orderDetail->setExhibition($exhibition);
            }

            // Charger l'objet Ticket à partir de l'ID
            $ticket = $ticketRepo->find($item['ticketId']);
            if ($ticket) {
                $orderDetail->setTicket($ticket);
            } 

            $orderDetail->setQuantity($item['qty']);
            $orderDetail->setUnitPrice($item['price']);
            // $orderDetail->setTotal($item['totalLine']);

            $entityManager->persist($orderDetail);
        }

        $entityManager->persist($order);
        $entityManager->flush();
        

        // Envoi de l'email de confirmation de commande
        $emailService->sendOrderConfirmationEmail($this->getUser(), $cart);
        //getUserIdentifier -> retourne identifiant unique qui est l'email (mep l11 de security.yaml)

        // Vider le panier après validation
        $cartService->clearCart();

        return $this->redirectToRoute('orderSuccess');
    }


 /********************** Commande validée *****************/
    #[Route('/orderSuccess', name: 'orderSuccess')]
    public function orderSuccess(): Response
    {
        return $this->render('order/orderSuccess.html.twig');
    }

}





    