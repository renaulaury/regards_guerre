<?php

namespace App\Controller\BackOffice;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TicketBOController extends AbstractController
{
    #[Route('/backOffice/stockManagement', name: 'stockManagement')]
    public function ticketStockManagement(): Response
    {
        return $this->render('backOffice/ticket/stockManagement.html.twig', [
        ]);
    }

    
}
