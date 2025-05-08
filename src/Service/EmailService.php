<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailService
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(
        string $to, string $subject, string $body, ?array $attachment = null, 
        string $from = 'noreply@regardsguerre.fr'
    ): void
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html($body);

            //Si le mail a des PJ alors on rajoute au mail
            if (is_array($attachment) && isset($attachment['content'], $attachment['filename'])) {
                
                $email->attach($attachment['content'], $attachment['filename'], $attachment['mimeType'] ?? null);
            }

        $this->mailer->send($email);
    }

    //Génére le contenu HTML a partir d'un template

    public function renderTemplate(string $templatePath, array $context = []): string
    {
        return $this->twig->render($templatePath, $context);
    }
}