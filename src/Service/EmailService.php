<?php

namespace App\Service;

use Twig\Environment;
use App\Service\CartService;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private CartService $cartService;
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(CartService $cartService, Environment $twig, MailerInterface $mailer)
    {
        $this->cartService = $cartService;
        $this->twig = $twig; // On récupère le service Twig pour créer un mail complet et personnalisé
        $this->mailer = $mailer;
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

        dump($cart); // Vérifie le contenu du panier
        $total = $this->cartService->getTotal();
        dump($total); // Vérifie que le total est bien calculé

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
