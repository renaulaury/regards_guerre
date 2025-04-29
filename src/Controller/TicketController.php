<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TicketController extends AbstractController
{
   #[Route('/ticket', name: 'ticket')]
    public function index(): Response
    {
        
        return $this->render('ticket.html.twig', [            
            'controller_name' => 'TicketController',
        ]); 
    }
}