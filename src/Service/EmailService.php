<?php

namespace App\Service;

use Twig\Environment;
use App\Service\CartService;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class EmailService
{
    private CartService $cartService;
    private MailerInterface $mailer;
    private Environment $twig;
    private RequestStack $requestStack;

    public function __construct(CartService $cartService, Environment $twig, MailerInterface $mailer, RequestStack $requestStack)
    {
        $this->cartService = $cartService;
        $this->twig = $twig; // On récupère le service Twig pour créer un mail complet et personnalisé
        $this->mailer = $mailer;
        $this->requestStack = $requestStack;
    }

    public function sendOrderConfirmationEmail($user, $cart): void
    {
         // On regroupe les tickets par exposition
        $groupedCart = [];

        foreach ($cart as $product) {
            // Insertion de l'expo dans le tableau
            if (!isset($groupedCart[$product['exhibitionId']])) {
                $groupedCart[$product['exhibitionId']] = [
                    'exhibition' => $product['exhibition'],
                    'tickets' => []
                ];
            }

            // Insertion du ticket dans le tableau
            if (!isset($groupedCart[$product['exhibitionId']]['tickets'][$product['ticketId']])) {
                $groupedCart[$product['exhibitionId']]['tickets'][$product['ticketId']] = [
                    'ticket' => $product['ticket'],
                    'quantity' => $product['qty'],
                    'price' => $product['price'],
                ];
            } else {
                // Sinon, on incrémente la quantité du ticket
                $groupedCart[$product['exhibitionId']]['tickets'][$product['ticketId']]['quantity'] += $product['qty'];
            }
        }

        // Récupérer le total du panier depuis la session
        $this->cartService->updateCartTotal($cart); 
        $session = $this->requestStack->getCurrentRequest()->getSession();        
        $total = $session->get('cartTotal');

        $email = (new Email())
            ->from('noreply@regardsguerre.fr')  // Expéditeur
            ->to($user->getUserIdentifier())  // Destinataire
            ->subject('Confirmation de votre commande')
            ->html(
                $this->twig->render('order/orderConfirmEmail.html.twig', [
                    'user' => $user,
                    'cart' => $cart,
                    'total' => $total,
                    'groupedCart' => $groupedCart, //Regroupement des achats
            ])
                );

        // Envoi de l'e-mail
        $this->mailer->send($email);
    }
}
