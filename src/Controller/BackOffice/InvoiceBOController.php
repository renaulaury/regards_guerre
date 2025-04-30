<?php

namespace App\Controller\BackOffice;

use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class InvoiceBOController extends AbstractController
{
    #[Route('/backOffice/comptabilite/factures-clients', name: 'listInvoices')]  
    public function listInvoices(
        InvoiceRepository $invoiceRepository
    ): Response
    {
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }
        
        $invoices = $invoiceRepository->findAll();

        return $this->render('/backOffice/accounting/listInvoices.html.twig', [
            'invoices' => $invoices, 
        ]);
    }
}
