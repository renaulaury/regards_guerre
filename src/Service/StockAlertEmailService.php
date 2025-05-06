<?php

namespace App\Service;

use App\Service\EmailService;

class StockAlertEmailService
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function sendStockAlertEmail(array $soonOutStockExhibits, array $outOfStockExhibitions): void
    {
        $body = $this->emailService->renderTemplate('emails/stockAlertEmail.html.twig', [
            'soonOutStockExhibits' => $soonOutStockExhibits,
            'outOfStockExhibitions' => $outOfStockExhibitions,
        ]);

        $this->emailService->send('alerte_stock@regardsguerre.fr', 'Alerte de stock', $body);
    }
}