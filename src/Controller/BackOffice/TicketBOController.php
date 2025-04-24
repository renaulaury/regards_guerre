<?php

namespace App\Controller\BackOffice;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TicketBOController extends AbstractController
{
    //Affiche la liste des expositions avec les niveaux de stocks
    #[Route('/backOffice/stockManagement/{filter?}', name: 'stockManagement')]
    public function ticketStockManagement(
        ExhibitionShareRepository $exhibitionShareRepo, 
        Request $request): Response
    {

        // Récupérer la valeur du filtre depuis la requête
        $filter = $request->query->get('filter');
       
        $exhibitions = $exhibitionShareRepo->findAllNextExhibition();
        $stockSoldOut = [];//Stock épuisé
        $stockSoonSoldOut = []; //Stock presque épuisé
        $allStocks = []; //Tous les stocks

        // Parcours chaque exposition récupérée
        foreach ($exhibitions as $exhibition) {
            $ticketsReserved = $exhibition->getTicketsReserved(); //Tickets réservés
            $ticketsRemaining = $exhibition->getTicketsRemaining(); //Tickets restants
            $stockMax = $exhibition->getStockMax();
            $stockAlert = $exhibition->getStockAlert();

            // Tabl associatif avec infos de stock pour l'expo courante
            $stockInfo = [
                'exhibition' => $exhibition,
                'reserved' => $ticketsReserved,
                'remaining' => $ticketsRemaining,
                'max' => $stockMax,
                'alert' => $stockAlert,
            ];

            $allStocks[] = $stockInfo;

            // Vérifie le niveau de stock
            if ($ticketsRemaining <= 0) {
                $stockSoldOut[] = $stockInfo;
            } elseif ($ticketsRemaining <= $stockAlert && $ticketsRemaining > 0) {
                $almostExhaustedStocks[] = $stockInfo;
            }
        }

        //Filtres les stocks selon le filtre
        $stocksToDisplay = match ($filter) {
            'epuise' => $stockSoldOut,
            'presque-epuise' => $stockSoonSoldOut,
            default => $allStocks, 
        };

        return $this->render('backOffice/ticket/stockManagement.html.twig', [
            'exhibitionStocks' => $stocksToDisplay,
            'currentFilter' => $filter, // Passer le filtre actuel au template pour la gestion des liens actifs
        ]);
    }
}
