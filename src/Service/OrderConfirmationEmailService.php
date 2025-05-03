<?php

namespace App\Service;

use App\Entity\Order;

class OrderConfirmationEmailService
{
    private PdfService $pdfService; 
    private EmailService $emailService;

    public function __construct(
        PdfService $pdfService,
        EmailService $emailService
    ) {
        $this->pdfService = $pdfService;
        $this->emailService = $emailService;
    }
    

    /************************  Génère le PDF des tickets à partir de l'entité order. *******************/
    public function generateEticketPdf(Order $order): ?array
    {
        //// Init du tabl pour les commandes regroupées
        $groupedOrderDetails = [];

        // Parcours des détails de la commande pour les regrouper par exposition
        foreach ($order->getOrderDetails() as $orderDetail) {
            $exhibitionId = $orderDetail->getExhibition()->getId();

            if (!isset($groupedOrderDetails[$exhibitionId])) {
                $groupedOrderDetails[$exhibitionId] = [
                    'exhibition' => $orderDetail->getExhibition(),
                    'orderDetails' => [],
                ];
            }
            // Ajoute le ticket à la liste
            $groupedOrderDetails[$exhibitionId]['orderDetails'][] = $orderDetail;
        }


        // Génère le contenu PDF (pdf service + template)
        $pdfContent = $this->pdfService->generatePdf(
            'pdf/eticketPdf.html.twig', 
            [
                'order' => $order,
                'groupedOrderDetails' => $groupedOrderDetails,
            ]
        );

        // Retourne le contenu et le nom du fichier PDF
        return [
            'content' => $pdfContent,
            'filename' => 'eticket.pdf',
        ];
    }


    /* Envoie les tickets par email à l'utilisateur.*/     
    public function sendTicketEmail(Order $order): void
    {
        //Génére le pdf de la facture
        $orderPdf = $this->generateEticketPdf($order);        

        if ($orderPdf) {
            $attachment = [
                'content' => $orderPdf['content'],
                'filename' => $orderPdf['filename'],
                'mimeType' => 'application/pdf',
            ];

            //Envoi l'email
            $this->emailService->send(
                $order->getCustomerEmail(),
                'Vos tickets',
                $this->emailService->renderTemplate(
                    'emails/eticketEmail.html.twig',
                    ['order' => $order]
                ),
                $attachment
            );
        }
    }
}