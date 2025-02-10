<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TicketController extends AbstractController
{
    #[Route('/ticket', name: 'ticket')]
    public function index(TicketRepository $ticketRepo): Response
    {

        $priceByExhibit = $ticketRepo->findAllTicketsByExhibition();

        return $this->render('ticket/index.html.twig', [
            'priceByExhibit' => $priceByExhibit,
            // 'exhibition' => $exhibition,
        //     'ticket' => $ticket,
            // 'tps' => $tps,            
        ]);
    }
}
