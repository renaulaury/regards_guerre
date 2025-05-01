<?php

namespace App\Service;

use App\Service\EmailService;

class StockAlertEmailService
{
    private EmailService $emailSenderService;

    public function __construct(EmailService $emailSenderService)
    {
        $this->emailSenderService = $emailSenderService;
    }

    public function sendStockAlertEmail(array $soonOutStockExhibits, array $outOfStockExhibitions): void
    {
        $body = $this->emailSenderService->renderTemplate('emails/stockAlertEmail.html.twig', [
            'soonOutStockExhibits' => $soonOutStockExhibits,
            'outOfStockExhibitions' => $outOfStockExhibitions,
        ]);

        $this->emailSenderService->send('alerte_stock@regardsguerre.fr', 'Alerte de stock', $body);
    }
}