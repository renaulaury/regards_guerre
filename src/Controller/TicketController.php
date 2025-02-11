<?php

namespace App\Controller;

use App\Repository\ExhibitionRepository;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TicketController extends AbstractController
{
    #[Route('/ticket', name: 'ticket')]
    public function index(TicketRepository $ticketRepo, ExhibitionRepository $exhibitRepo): Response
    {
         
        $priceByExhibit = $ticketRepo->findAllTicketsByExhibition();

        $exhibitions = $exhibitRepo->findAllNextExhibition();

        return $this->render('ticket/index.html.twig', [
            'priceByExhibit' => $priceByExhibit,
            'exhibitions' => $exhibitions,
            // 'ticket' => $ticket,
            // 'tps' => $tps,            
        ]);
    }
}


