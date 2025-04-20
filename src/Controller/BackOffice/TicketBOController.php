<?php

namespace App\Controller\BackOffice;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TicketBOController extends AbstractController
{
    #[Route('/backOffice/stockManagement', name: 'stockManagement')]
    public function ticketStockManagement(ExhibitionShareRepository $exhibitionShareRepo): Response
    {
        $exhibitions = $exhibitionShareRepo->findAllNextExhibition();
        $exhibitionStocks = []; // Tableau pour stocker les infos de stock par exposition


        foreach ($exhibitions as $exhibition) {
            $ticketsReserved = $exhibition->getTicketsReserved();
            $ticketsRemaining = $exhibition->getTicketsRemaining();
            $stockMax = $exhibition->getStockMax();
            $stockAlert = $exhibition->getStockAlert();

            $exhibitionStocks[] = [
                'exhibition' => $exhibition,
                'reserved' => $ticketsReserved,
                'remaining' => $ticketsRemaining,
                'max' => $stockMax,
                'alert' => $stockAlert,
            ];
    }            

        return $this->render('backOffice/ticket/stockManagement.html.twig', [
            'exhibitionStocks' => $exhibitionStocks,
        ]);
    }

    
}
