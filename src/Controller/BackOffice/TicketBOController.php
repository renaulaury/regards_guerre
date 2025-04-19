<?php

namespace App\Controller\BackOffice;


use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TicketBOController extends AbstractController
{
    #[Route('/backOffice/stockManagement', name: 'stockManagement')]
    public function ticketStockManagement(
        ExhibitionShareRepository $exhibitionShareRepo,
        Security $security): Response
    {
        $exhibitions = $exhibitionShareRepo->findAllNextExhibition();
        $exhibitionStocks = []; // Tableau pour stocker les infos de stock par exposition

        $msgStockAlert = false; // Variable le msg d'alerte de stock 

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

        // Vérifie si le stock restant est inférieur ou égal au niveau d'alerte
        if ($ticketsRemaining <= $stockAlert) {
            $msgStockAlert = true; // On a au moins une exposition en alerte
            
        }
    }

        // Ajoute un message flash si une alerte de stock est détectée
        if ($msgStockAlert && ($security->isGranted('ROLE_ROOT') || $security->isGranted('ROLE_ADMIN'))) {
            $this->addFlash(
                'warning',
                'ATTENTION : Merci de vérifier l\'état des stocks, le seuil d\'alerte a été atteint pour au moins une exposition.'
            );
        }

        return $this->render('backOffice/ticket/stockManagement.html.twig', [
            'exhibitionStocks' => $exhibitionStocks,
        ]);
    }

    
}
