<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Exhibition;
use App\Repository\TicketRepository;
use App\Repository\ExhibitionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TicketController extends AbstractController
{
   #[Route('/ticket', name: 'ticket')]
    public function index(TicketRepository $ticketRepo, ExhibitionRepository $exhibitRepo): Response
    {
        //Récup de tous les tickets par exposition 
        $priceByExhibit = $ticketRepo->findAllTicketsByExhibition();

        //Récup uniquement les infos des prochaines expos
        $exhibitions = $exhibitRepo->findAllNextExhibition();

        return $this->render('ticket/index.html.twig', [
            'priceByExhibit' => $priceByExhibit,
            'exhibitions' => $exhibitions,           
        ]);
}

    
}



