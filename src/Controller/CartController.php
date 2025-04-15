<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Service\CartService;
use App\Service\EmailService;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    private CartService $cartService;
    private RequestStack $requestStack;
    private EmailService $emailService;
    private EntityManagerInterface $entityManager;

    public function __construct(CartService $cartService, RequestStack $requestStack, EmailService $emailService, EntityManagerInterface $entityManager)
  {    
    $this->cartService = $cartService;
    $this->requestStack = $requestStack;
    $this->emailService = $emailService;
    $this->entityManager = $entityManager;
  }

     /************* Affiche les produits du panier ***************/
     #[Route('/order/cart', name: 'cart')]
     public function showCart(): Response
     {
        // Récupérer le panier depuis le service
         $cart = $this->cartService->getCart();
         $total = $this->cartService->getTotal();
         $groupedCart = $this->cartService->groupCartByExhibition($cart);
         
         return $this->render('order/cart.html.twig', [
            'groupedCart' => $groupedCart,
             'cart' => $cart,
             'total' => $total,
         ]);
     }
 

    /************* Ajoute un ticket au panier  ***************/
    #[Route('/ticket/{exhibitionId}/addTicketToCart/{ticketId}/{origin}', name: 'addTicketToCart')]
    public function addTicketToCart(TicketRepository $ticketRepo, int $exhibitionId, int $ticketId, string $origin): Response
    {
        // Récupération du ticket via le repository
        $ticket = $ticketRepo->find($ticketId);


        // Ajout du ticket au panier via le service
        $this->cartService->addCart($ticket, $exhibitionId);

        // Redirection en fonction de l'origine
        if ($origin === 'ticket') {
            return $this->redirectToRoute('ticket', ['exhibition' => $exhibitionId]);
        }

        // Redirection par défaut
        return $this->redirectToRoute('cart', ['exhibition' => $exhibitionId]);
    }

/************* Soustrait un produit au panier ***************/
    #[Route('/ticket/{exhibitionId}/removeTicketFromCart/{ticketId}/{origin}', name: 'removeTicketFromCart')]
    public function removeTicketFromCart(TicketRepository $ticketRepo, int $exhibitionId, int $ticketId, string $origin)
    // : Response
    {

         // Récupération du ticket via le repository
         $ticket = $ticketRepo->find($ticketId);

        // Soustraction au panier via le service
        $this->cartService->removeCart($ticket, $exhibitionId);

        if ($origin === 'ticket') { // Si l'origine est 'ticket', rediriger vers la page de ventes des tickets
            return $this->redirectToRoute('ticket', [
                'exhibition' => $exhibitionId
            ]);
        }

        if ($origin === 'cart') { // Si l'origine est 'cart', rediriger vers le panier
            return $this->redirectToRoute('cart', [
                'exhibition' => $exhibitionId,
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
    public function deleteCart(): Response
    {
        $this->cartService->clearCart(); // Vide le panier

        return $this->redirectToRoute('cart'); // Redirige vers la page du panier
    }

/********************** Retirer un article du panier *****************/
    #[Route('/order/cart/remove/{id}', name: 'removeProduct')]
    public function removeProductToCart(int $id): Response
    {
        $this->cartService->removeProduct($id); // Appelle la fonction pour supprimer l'élément

        return $this->redirectToRoute('cart'); 
    }


/********************** Valider la commande *****************/
    #[Route('/order/cart/orderValidated/{id}', name: 'orderValidated')]
    public function orderValidated(ExhibitionShareRepository $exhibitShareRepo, TicketRepository $ticketRepo): Response
    {
        $cart = $this->cartService->getCart();
        
        // Création d'une nouvelle commande
        $order = new Order();
        $order->setOrderDateCreation(new \DateTimeImmutable()); //Avec une date immuable (const)
        $order->setUser($this->getUser());
        $order->setOrderStatus('Envoyé'); 
        
        foreach ($cart as $item) {
            $orderDetail = new OrderDetail();
            $orderDetail->setOrder($order);
            
            // Charger l'objet Exhibition à partir de l'ID
            $exhibition = $exhibitShareRepo->find($item['exhibitionId']); 
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

            $this->entityManager->persist($orderDetail);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // Calcul de $total et $groupedCart
        $groupedCart = $this->cartService->groupCartByExhibition($cart); //Regroupe les articles du panier
        $this->cartService->updateCartTotal($cart); //Maj panier
        $session = $this->requestStack->getCurrentRequest()->getSession(); 
        $total = $session->get('cartTotal'); //Récup total panier
        

        // Envoi de l'email de confirmation de commande
        $this->emailService->sendOrderConfirmationEmail($this->getUser(), $cart, $total, $groupedCart);
        

        // Vider le panier après validation
        $this->cartService->clearCart();

        return $this->redirectToRoute('orderSuccess');
    }


 /********************** Commande validée *****************/
    #[Route('/orderSuccess', name: 'orderSuccess')]
    public function orderSuccess(): Response
    {
        return $this->render('order/orderSuccess.html.twig');
    }

}





