<?php

namespace App\Service;

use Twig\Environment;
use Symfony\Component\Mime\Attachment;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface ;

class EmailService
{
    private Environment $twig;    
    private MailerInterface $mailer;

    public function __construct(Environment $twig, MailerInterface $mailer)
    {
        $this->twig = $twig; 
        $this->mailer = $mailer;     
    }

/********************* Envoi d'email *************************/
    public function sendEmail(string $to, string $subject, string $body, array $attachments = []): void
    {
        $email = (new Email())
            ->from('noreply@regardsguerre.fr')
            ->to($to)
            ->subject($subject)
            ->html($body);

        // PJ
        foreach ($attachments as $attachment) {
            if (isset($attachment['content']) && isset($attachment['filename'])) {
                $email->addAttachment($attachment['content'], $attachment['filename'], 'application/pdf'); 
            }
        }

        //Envoi
        $this->mailer->send($email);
    }

/********************* Envoie un email de confirmation de commande à un utilisateur *************************/
    public function sendOrderConfirmationEmail($user, $cart, $total, $groupedCart): void
    {
        //Contenu -> template
        $body = $this->twig->render('emails/orderConfirmEmail.html.twig', [
            'user' => $user,
            'cart' => $cart,
            'total' => $total,
            'groupedCart' => $groupedCart,
        ]);

        //Envoi
        $this->sendEmail($user->getUserIdentifier(), 'Confirmation de votre réservation', $body);
        //permet d identifier le user par la clé unique mail
    }

/********************* Envoie un email d'alerte de stock à un root/admin *************************/

    public function sendStockAlertEmail(array $soonOutStockExhibits, array $outOfStockExhibitions): void
    {
        //Contenu -> template
        $body = $this->twig->render('emails/stockAlertEmail.html.twig', [
        'soonOutStockExhibits' => $soonOutStockExhibits,
            'outOfStockExhibitions' => $outOfStockExhibitions,
        ]);

        //Envoi
        $this->sendEmail('alerte_stock@regardsguerre.fr', 'Alerte de stock', $body);
    }
}