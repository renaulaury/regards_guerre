<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Service\CartService;
use App\Service\EmailService;
use App\Repository\TicketRepository;
use App\Form\UserEditIdentityFormType;
use App\Form\UserIdentityCartFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    public function __construct(
        CartService $cartService, 
        RequestStack $requestStack, 
        EmailService $emailService, 
        EntityManagerInterface $entityManager)
  {    
    $this->cartService = $cartService;
    $this->requestStack = $requestStack;
    $this->emailService = $emailService;
    $this->entityManager = $entityManager;
  }

     /************* Affiche les produits du panier ***************/
     #[Route('/order/cart', name: 'cart')]
     public function showCart(?User $user): Response //Permet au user de voir son panier meme déco
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
        ExhibitionShareRepository $exhibitionRepo): Response
    {
        // Récup du ticket via le repository
        $ticket = $ticketRepo->find($ticketId);

        // Récup de l'exposition via le repository en utilisant l'ID de la route
        $exhibition = $exhibitionRepo->find($exhibitionId);


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
        ExhibitionShareRepository $exhibitionRepo)
        // : Response
    {
         // Récupération du ticket via le repository
         $ticket = $ticketRepo->find($ticketId);

         // Récup de l'exposition via le repository en utilisant l'ID de la route
         $exhibition = $exhibitionRepo->find($exhibitionId);

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
    public function removeProductToCart(
        string $id): Response
    {
        $this->cartService->removeProduct($id); // Appelle la fonction pour supprimer l'élément

        return $this->redirectToRoute('cart'); 
    }


/********************** Valider la commande *****************/
    #[Route('/order/cart/orderValidated', name: 'orderValidated')]
    public function orderValidated(
        ExhibitionShareRepository $exhibitShareRepo, 
        TicketRepository $ticketRepo,
        Request $request,
        ?User $user,
        EntityManagerInterface $entityManager): Response
    {        
        $form = $this->createForm(UserIdentityCartFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer la valeur de la case à cocher 'saveIdentity'
            $saveIdentity = $form->get('saveIdentity')->getData();

            // Si la case est cochée et qu'un utilisateur est connecté, on enregistre ses informations
            if ($saveIdentity && $user) {
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Vos informations ont été enregistrées pour vos prochaines commandes.');
            }
        }

        $cart = $this->cartService->getCart();        
        $stockErrors = []; //Gestion des erreurs de stock/panier
        $soonOutStockExhibits = []; // Gestion des expo presque épuisées
        $outOfStockExhibitions = []; // Gestion des expo épuisées

        // Vérification init du stock et collecte des erreurs de stock
        foreach ($cart as $item) {
            $exhibition = $exhibitShareRepo->find($item['exhibitionId']); //Récup id expo like id dans panier
            if ($exhibition) { 
                //Vérif si les tickets commandés sont dispos
                $ticketsAvailable = $exhibition->getStockMax() - $exhibition->getTicketsReserved();
                $qtyRequested = $item['qty']; //Qté demandée

                if ($qtyRequested > $ticketsAvailable) {
                    $stockErrors[] = [
                        'exhibitionTitle' => $exhibition,
                        'ticketsAvailable' => $ticketsAvailable,
                    ];
                }
            }
        }

        if (!empty($stockErrors)) {
            foreach ($stockErrors as $error) {
                $this->addFlash(
                    'danger',
                    sprintf( //Permet concaténation string + variable
                        'Stock insuffisant pour l\'exposition "%s". (%d restants).',
                        $error['exhibitionTitle'],
                        $error['ticketsAvailable']
                    )
                );
            }

            return $this->redirectToRoute('cart'); // Redirige vers le panier pour modification
        }
       
        // Création d'une nouvelle commande
        $order = new Order();
        $order->setOrderDateCreation(new \DateTimeImmutable()); //Avec une date immuable (const)
        $order->setUser($this->getUser());
        $order->setOrderStatus('Envoyé'); 
        dump($this->getUser());die;
        dump($this->getUser()->getUserEmail());die;
        $order->setCustomerEmail($this->getUser()->getUserEmail());

        // Vérif si un user est co ET si son nom et prénom ne sont pas vides.
        if ($this->getUser() && !empty($this->getUser()->getUserName()) && !empty($this->getUser()->getUserFirstname())) {// Récup le nom et prénom de l'utilisateur connecté dans la bdd
            $order->setCustomerName($this->getUser()->getUserName());
            $order->setCustomerFirstname($this->getUser()->getUserFirstname());
        } else {
            // Récup le nom et prénom du user dans le form
            $order->setCustomerName($form->get('userName')->getData());
            $order->setCustomerFirstname($form->get('userFirstname')->getData());
        }

        // Enregistrement du total de la commande
        $cart = $this->cartService->getCart();
        $total = $this->cartService->getTotal(); 
        $order->setOrderTotal($total); 
        
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


        /********* Envoi confirm commande au user ***********/
        // Calcul de $total et $groupedCart
        $groupedCart = $this->cartService->groupCartByExhibition($cart); //Regroupe les articles du panier
        $this->cartService->updateCartTotal($cart); //Maj panier
        $session = $this->requestStack->getCurrentRequest()->getSession(); 
        $total = $session->get('cartTotal'); //Récup total panier
        

        // Envoi de l'email de confirmation de commande
        $this->emailService->sendOrderConfirmationEmail($this->getUser(), $cart, $total, $groupedCart);

        /********* Envoi de l'email d'alerte de stock à l'admin/root ***********/
        $this->entityManager->refresh($exhibition); //raffraichit l'expo pour avoir la maj
        // Vérification des stocks APRÈS l'enregistrement de la commande en parcourant le groupedCart
        foreach ($groupedCart as $exhibitionId => $items) { //Parcours groupedCart (tabl AM)
            $exhibition = $exhibitShareRepo->find($exhibitionId);
            
            if ($exhibition) {
                $remainingStock = $exhibition->getStockMax() - $exhibition->getTicketsReserved(); // Calcul du stock restant

                if ($remainingStock <= $exhibition->getStockAlert() && $remainingStock > 0 && !in_array($exhibition, $soonOutStockExhibits)) {
                    $soonOutStockExhibits[] = $exhibition; // si stock presque épuisé alors expo dans le tableau
                }
                if ($remainingStock <= 0 && !in_array($exhibition, $outOfStockExhibitions)) {
                    $outOfStockExhibitions[] = $exhibition; // si stock épuisé alors expo dans le tableau
                }
            }
        }

        // Envoi de l'email d'alerte de stock à l'admin
        $this->emailService->sendStockAlertEmail(array_unique($soonOutStockExhibits), array_unique($outOfStockExhibitions));

       

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





