<?php

namespace App\Service;

use App\Entity\Invoice;

class InvoiceService
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
    

    /* Génère le PDF de la facture à partir de l'entité Invoice.*/
    public function generateInvoicePdf(Invoice $invoice): ?array
    {
        // Génère le contenu PDF (pdf service + template)
        $pdfContent = $this->pdfService->generatePdf(
            'pdf/invoicePdf.html.twig', 
            ['invoice' => $invoice]
        );

        // Retourne le contenu et le nom du fichier PDF
        return [
            'content' => $pdfContent,
            'filename' => 'facture_' . $invoice->getNumberInvoice() . '.pdf',
        ];
    }


    /* Envoie la facture par email à l'utilisateur.*/     
    public function sendInvoiceEmail(Invoice $invoice): void
    {
        //Génére le pdf de la facture
        $invoicePdf = $this->generateInvoicePdf($invoice);        

        if ($invoicePdf) {
            $attachment = [
                'content' => $invoicePdf['content'],
                'filename' => $invoicePdf['filename'],
                'mimeType' => 'application/pdf',
            ];

            //Envoi l'email
            $this->emailService->send(
                $invoice->getCustomerEmail(),
                'Votre facture #' . $invoice->getNumberInvoice(),
                $this->emailService->renderTemplate(
                    'emails/invoiceEmail.html.twig',
                    ['invoice' => $invoice]
                ),
                $attachment
            );
        }
    }
}