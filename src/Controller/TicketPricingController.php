<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TicketPricingController extends AbstractController
{
    #[Route('/ticket/pricing', name: 'app_ticket_pricing')]
    public function index(): Response
    {
        return $this->render('ticket_pricing/index.html.twig', [
            'controller_name' => 'TicketPricingController',
        ]);
    }
}
