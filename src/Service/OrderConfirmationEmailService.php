<?php

namespace App\Service;

use App\Service\EmailService;
use App\Service\OrderExportPdfService;


/* Service qui envoie facture + tickets en PJ */

class OrderConfirmationEmailService
{
    private EmailService $emailService;
    private OrderExportPdfService $orderExportPdfService;

    public function __construct(EmailService $emailService, OrderExportPdfService $orderExportPdfService)
    {
        $this->emailService = $emailService;
        $this->orderExportPdfService = $orderExportPdfService;
    }

    public function sendOrderConfirmationEmailWithAttachments(int $orderId, $user, $cart, $total, $groupedCart, string $recipientEmail = null): void
    {
        // Générer le PDF de la facture
        $invoicePdfData = $this->orderExportPdfService->generateInvoicePdf($orderId);

        // Générer les PDFs des e-tickets en utilisant le service dédié
        $eTicketsPdfData = $this->orderExportPdfService->generateTicketsPdf($orderId, $groupedCart); // Correction du nom de la méthode

        // Init tableau pj
        $attachments = [];

        // Add facture
        if ($invoicePdfData) {
            $attachments[] = [
                'content' => $invoicePdfData['content'],
                'filename' => $invoicePdfData['filename'],
                'mimeType' => 'application/pdf',
            ];
        }

        // Add e-tickets
        if ($eTicketsPdfData) {
            foreach ($eTicketsPdfData as $eTicketData) {
                $attachments[] = [
                    'content' => $eTicketData['content'],
                    'filename' => $eTicketData['filename'],
                    'mimeType' => 'application/pdf',
                ];
            }
        }

        // Rendre le corps de l'e-mail en utilisant le template Twig
        $body = $this->emailService->renderTemplate('emails/orderConfirmEmail.html.twig', [
            'user' => $user,
            'cart' => $cart,
            'total' => $total,
            'groupedCart' => $groupedCart,
        ]);

        // Déterminer le destinataire de l'e-mail
        $to = $recipientEmail ?? $user->getUserIdentifier();

        // Envoyer l'e-mail en utilisant le service d'envoi générique avec les pièces jointes
        $this->emailService->send(
            $to,
            'Confirmation de votre réservation et vos documents',
            $body,
            $attachments
        );
    }
}