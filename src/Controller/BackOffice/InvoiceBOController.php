<?php

namespace App\Controller\BackOffice;

use App\Entity\Invoice;
use App\Service\InvoiceService;
use App\Repository\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BackOffice\InvoiceBORepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class InvoiceBOController extends AbstractController
{
    /* Affiche la liste des clients possédant une facture */
    #[Route('/backOffice/accounting/listInvoices', name: 'listInvoices')]
    public function listInvoices(
        InvoiceBORepository $invoiceBORepo,
        Request $request): Response
    {
        // Vérification de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

        // Récup le paramètre de la requête pour le filtre par lettre (sur le nom)
        $letter = $request->query->get('letter');

        // Récupérer toutes les factures
        $allInvoices = $invoiceBORepo->findInvoices();

        // Filtrer les factures par la première lettre du nom
        $invoices = $allInvoices;
        if ($letter) {
            $invoices = array_filter($invoices, function ($invoice) use ($letter) {
                return str_starts_with(strtolower($invoice->getCustomerName()), strtolower($letter));
            });
        }

        // Préparer les lettres pour le filtre 
        $availableLetters = $this->getFirstLetter($invoiceBORepo);

        // Identifier les clients uniques par email et récup leurs infos
        $uniqueCustomers = [];
        foreach ($invoices as $invoice) {
            $customerEmail = $invoice->getCustomerEmail();
            if (!isset($uniqueCustomers[$customerEmail])) {
                $uniqueCustomers[$customerEmail] = [
                    'name' => $invoice->getCustomerName(),
                    'firstname' => $invoice->getCustomerFirstname(),
                    'email' => $customerEmail,
                    'slug' => $invoice->getSlug(),
                ];
            }
        }

        // Rendre le template en passant les données nécessaires
        return $this->render('/backOffice/accounting/listInvoices.html.twig', [
            'uniqueCustomers' => array_values($uniqueCustomers),
            'availableLetters' => $availableLetters,
            'selectedLetter' => $letter,
        ]);
    }
    

    private function getFirstLetter(InvoiceBORepository $invoiceBORepo): array
    {
        $invoices = $invoiceBORepo->findInvoices(); // Récup toutes les factures
        $letters = array_unique(array_map(function ($invoice) {
            // Récup la première lettre du nom du client et la mettre en majuscule
            return strtoupper(substr($invoice->getCustomerName(), 0, 1));
        }, $invoices));
        sort($letters); // Tri alphabétique
        return $letters;
    }


/*********** Affiche l'historique de factures de l'utilisateur ************************/
    #[Route('/backOffice/accounting/invoicesUserBO/{slug}', name: 'invoicesUserBO')]           
    public function invoicesUserBO(
        string $slug,
        InvoiceBORepository $invoiceBORepo
    ): Response
    {
        // Vérification de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

        $invoices = $invoiceBORepo->findInvoicesByYear(['slug' => $slug]);


        // Passer les factures et la première facture (pour le nom du client)
        return $this->render('backOffice/accounting/invoicesHistory.html.twig', [
            'invoices' => $invoices,
            'firstInvoice' => $invoices[0] ?? null, // Pour récupérer les infos du client
            'slug' => $slug,
        ]);
    }

/***************** Envoie de la facture en pdf ***********************/
    #[Route('/backOffice/user/userInvoiceExport/{idInvoice}', name: 'userAccountingInvoiceExportBO')]
    public function userAccountingInvoiceExportBO(
        int $idInvoice,
        InvoiceRepository $invoiceRepository,
        InvoiceService $invoiceService
    ): Response {
        
        // Vérification de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

        // Récupère l'entité Invoice à partir de l'ID
        $invoice = $invoiceRepository->find($idInvoice);

        // Vérifie si la facture existe.
        if (!$invoice) {
            $this->addFlash('error', 'Facture non trouvée.');
            return $this->redirectToRoute('invoicesUserBO');
        }

        // Envoie l'email de la facture à l'utilisateur
        $invoiceService->sendInvoiceEmailBO($invoice);

        $this->addFlash('success', 'La facture N°' . $invoice->getNumberInvoice() . ' vous a été envoyée par mail.');
        return $this->redirectToRoute('invoicesUserBO', ['slug' => $invoice->getSlug()]); 

    }
}
