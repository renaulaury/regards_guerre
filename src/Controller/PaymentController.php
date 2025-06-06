<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Invoice;
use App\Entity\OrderDetail;
use App\Service\CartService;
use Stripe\Checkout\Session;
use App\Repository\TicketRepository;
use App\Form\UserIdentityCartFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\StockAlertEmailService;
use App\Service\OrderConfirmationEmailService;


class PaymentController extends AbstractController
{
    private CartService $cartService;
    private RequestStack $requestStack;
    private StockAlertEmailService $stockAlertEmailService;
    private OrderConfirmationEmailService $orderConfirmEmailService;
    private EntityManagerInterface $entityManager;

    public function __construct(
        CartService $cartService, 
        RequestStack $requestStack, 
        EntityManagerInterface $entityManager,
        StockAlertEmailService $stockAlertEmailService,
        OrderConfirmationEmailService $orderConfirmEmailService)
    {
        $this->cartService = $cartService;
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
        $this->stockAlertEmailService = $stockAlertEmailService;
        $this->orderConfirmEmailService = $orderConfirmEmailService;
    }

    #[Route('/order/create-session-stripe', name: 'paymentStripe')]
    public function stripeCheckout(        
        UrlGeneratorInterface $urlGenerator,
        TicketRepository $ticketRepo,
        ExhibitionShareRepository $exhibitionShareRepo,
        Request $request): RedirectResponse|Response
    {   
        $form = $this->createForm(UserIdentityCartFormType::class, $this->getUser());        

        //Si nom prenom !bdd        
        if (!$this->getUser()->getUserName() && !$this->getUser()->getUserFirstname()) {
            
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //Save en session car paiement non validé
                $session = $this->requestStack->getCurrentRequest()->getSession();
                $session->set('customerName', $form->get('userName')->getData());
                $session->set('customerFirstname', $form->get('userFirstname')->getData());
                $session->set('saveIdentity', $form->get('saveIdentity')->getData());

            } else {
                // Ajouter un message flash d'erreur
                $this->addFlash(
                    'error',
                    'Veuillez renseigner votre nom et prénom pour continuer la commande.'
                );
            
                // Réafficher le form avec les erreurs
                return $this->render('order/cart.html.twig', [
                    'form' => $form->createView(), //Converti l'objet form en format Twig
                    'groupedCart' => $this->cartService->groupCartByExhibition($this->cartService->getCart()),
                    'cart' => $this->cartService->getCart(),
                    'total' => $this->cartService->getTotal(),
                ]);
            }
        }        

        //Récup panier pour vérif stock + préparation donnée pour Stripe
        $cart = $this->cartService->getCart();  

        //Vérif stock avant paiement
        $stockErrors = []; //Gestion des erreurs de stock/panier

        // Vérification init du stock et collecte des erreurs de stock
        foreach ($cart as $item) {
            $exhibition = $exhibitionShareRepo->find($item['exhibitionId']); //Récup id expo like id dans panier
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

        //Si différence de stock alors msg d'erreur
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


        /************** Prépare les informations nécessaire à envoyer à Stripe **************************/

        $productStripe = [];
            
        
        foreach ($cart as $product) {            
            $ticket = $ticketRepo->find($product['ticketId']);
            $exhibition = $exhibitionShareRepo->find($product['exhibitionId']);
            $qty = $product['qty'];
            $price = $product['price'];

            $productStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $ticket->getTitleTicket(),
                    ],
                    'unit_amount' => $price * 100, // Prix en centimes
                ],
                'quantity' => $qty,
            ];
        }

        
        // Configure la clé API de Stripe pour l'authentification.

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        // Génération des URLs de succès et d'annulation du paiement

        $successUrl = $urlGenerator->generate('paymentSuccess', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $cancelUrl = $urlGenerator->generate('paymentError', [], UrlGeneratorInterface::ABSOLUTE_URL);
        
        $checkoutSession = Session::create([
            'customer_email' => $this->getUser()->getUserIdentifier(),
            'payment_method_types' => ['card'],
            'line_items' => $productStripe,
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
        
        // Redirige l'utilisateur vers l'URL de paiement fournie par Stripe

        return new RedirectResponse($checkoutSession->url, 303); //303 : On sort du site pour aller sur Stripe      
    }

    /********************** Erreur de paiement ********************/
    #[Route('/order/error', name: 'paymentError')]
    public function stripeError(): Response
    {
        $this->addFlash('danger', 'Une erreur est survenue lors du paiement. Veuillez réessayer.');

        return $this->redirectToRoute('cart'); 
    }

    /********************** Succès de paiement ********************/
    #[Route('/order/success', name: 'paymentSuccess')]
    public function stripeSuccess(
        ExhibitionShareRepository $exhibitShareRepo, 
        TicketRepository $ticketRepo,
        RequestStack $requestStack,
        CartService $cartService): Response
    {
        //Récup des infos du form stockés en session
        $session = $requestStack->getCurrentRequest()->getSession();
        $customerName = $session->get('customerName'); 
        $customerFirstname = $session->get('customerFirstname'); 
        $saveIdentity = $session->get('saveIdentity'); 

        $user = $this->getUser(); //Récup user co
        

        //Si $saveIdentity = true alors enregistrement dans user
        if ($saveIdentity && $user) {
            $user->setUserName($customerName); 
            $user->setUserFirstname($customerFirstname); 
            $this->entityManager->persist($user); 
            $this->entityManager->flush(); 
            $this->addFlash('success', 'Vos informations ont été enregistrées pour vos prochaines commandes.');
        }


        $cart = $this->cartService->getCart(); //Récup panier   
        
        
        // Vérif si un user est co ET si son nom et prénom ne sont pas vides
        $order = new Order(); 
        $order->setOrderDateCreation(new \DateTimeImmutable()); // //Avec une date immuable (const)
        $order->setUser($this->getUser()); // Associe la commande au user co
        $order->setOrderStatus('Envoyé'); 

        // Récup le nom et prénom du client. Priorité à la session (formulaire), sinon BDD de l'utilisateur connecté.
        $order->setCustomerName($customerName);
        $order->setCustomerFirstname($customerFirstname);

        $order->setCustomerEmail($this->getUser()->getUserIdentifier()); // Enregistre l'email de l'utilisateur

        // Enregistrement du total de la commande
        $total = $cartService->getTotal(); // Récupère le total du panier
        $order->setOrderTotal($total); // Enregistre le total dans la commande

        // Création des détails de la commande        
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

        // Persist de l'order AVANT de générer le numéro de facture
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // Rafraîchir l'entité pour récupérer l'ID généré par la bdd
        $this->entityManager->refresh($order);


        // Création de la facture
        $invoice = new Invoice();
        $invoice->setCustomerName($order->getCustomerName());
        $invoice->setCustomerFirstname($order->getCustomerFirstname());
        $invoice->setCustomerEmail($order->getCustomerEmail());
        $invoice->setOrderTotal($order->getOrderTotal());
        $invoice->setDateInvoice(new \DateTimeImmutable()); // Utilise une date immutable

        // Génération du numéro de facture unique
        $orderId = $order->getId();
        $orderDate = $order->getOrderDateCreation()->format('Ymd');
        
        $invoiceNumber = sprintf('%s-%s', $orderDate, $orderId);
        $order->setNumberInvoice($invoiceNumber);
        $invoice->setNumberInvoice($invoiceNumber);


        // Récup de l'email
        $userEmail = $order->getUser()->getUserIdentifier();
        $emailBegin = strstr($userEmail, '@', true); // strstr() récupère tout avant le "@"

        // Générer le slug avec l'ID de l'utilisateur et la partie avant l'@
        $userId = $order->getUser()->getId();
        $slug = sprintf('%d-%s', $userId, $emailBegin);

        // Affecter le slug à la facture
        $invoice->setSlug($slug);



        $invoiceDetails = []; //Détail de la commande

        //Récup les éléments du panier
        foreach ($cart as $item) {
            $exhibition = $exhibitShareRepo->find($item['exhibitionId']);
            $ticket = $ticketRepo->find($item['ticketId']);
            $quantity = $item['qty'];
            $price = $item['price'];
    
            $invoiceDetails[] = [
                'exhibitionTitle' => $exhibition->getTitleExhibit(),
                'ticketTitle' => $ticket->getTitleTicket(),
                'standardPrice' => $price,
                'quantity' => $quantity,
            ];
        }
    
        $invoice->setInvoiceDetails($invoiceDetails);

                      

        $this->entityManager->persist($order);
        $this->entityManager->persist($invoice);
        $this->entityManager->flush();
      
                

       // Envoi de l'email de confirmation de commande
       $this->orderConfirmEmailService->sendTicketEmail($order);
        

        /********* Envoi de l'email d'alerte de stock à l'admin/root ***********/

        $groupedCart = $this->cartService->groupCartByExhibition($cart); //Regroupe les articles du panier        

        $this->entityManager->refresh($exhibition); //raffraichit l'expo pour avoir la maj

         //Gestion des stocks        
         $soonOutStockExhibits = []; // Gestion des expo presque épuisées
         $outOfStockExhibitions = []; // Gestion des expo épuisées

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
        if (!empty($soonOutStockExhibits) || !empty($outOfStockExhibitions)) {
            $this->stockAlertEmailService->sendStockAlertEmail(array_unique($soonOutStockExhibits), array_unique($outOfStockExhibitions));
        }

       

        // Vider le panier après validation
        $this->cartService->clearCart();

        return $this->redirectToRoute('orderSuccess');
    }

    
    #[Route('/order/success/confirmation', name: 'orderSuccess')]
    public function orderSuccess(): Response
    {
        
        return $this->render('order/orderSuccess.html.twig');
    }

}