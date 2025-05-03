<?php

namespace App\Service;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceService
{
    private EntityManagerInterface $entityManager;
    private PdfService $pdfService; // Injection de PdfService directement
    private EmailService $emailService;

    public function __construct(
        EntityManagerInterface $entityManager,
        PdfService $pdfService,
        EmailService $emailService
    ) {
        $this->entityManager = $entityManager;
        $this->pdfService = $pdfService;
        $this->emailService = $emailService;
    }

    /**
     * Crée et enregistre une entité Invoice à partir des données du panier.
     *
     * @param array $cart
     * @param string $customerName
     * @param string $customerFirstname
     * @param string $customerEmail
     * @param string $orderTotal
     * @param string $invoiceNumber
     * @return Invoice
     */
    public function createInvoice(
        array $cart,
        string $customerName,
        string $customerFirstname,
        string $customerEmail,
        string $orderTotal,
        string $invoiceNumber
    ): Invoice {
        $invoice = new Invoice();
        $invoice->setNumberInvoice($invoiceNumber);
        $invoice->setCustomerName($customerName);
        $invoice->setCustomerFirstname($customerFirstname);
        $invoice->setCustomerEmail($customerEmail);
        $invoice->setOrderTotal($orderTotal);
        $invoice->setDateInvoice(new \DateTimeImmutable());

        $invoiceDetails = [];
        foreach ($cart as $item) {
            $invoiceDetails[] = [
                'expositionTitle' => $item['exhibitionTitle'] ?? null,
                'ticketTitle' => $item['ticketTitle'] ?? null,
                'standardPrice' => $item['price'] ?? null,
                'quantity' => $item['qty'] ?? null,
            ];
        }
        $invoice->setInvoiceDetails($invoiceDetails);

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        return $invoice;
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