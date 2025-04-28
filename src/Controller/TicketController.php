<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TicketController extends AbstractController
{
   #[Route('/ticket', name: 'ticket')]
    public function index(        
        ExhibitionShareRepository $exhibitShareRepo): Response
    {
        //Récup uniquement les infos des prochaines expos
        $exhibitions = $exhibitShareRepo->findAllNextExhibition();


        return $this->render('ticket/index.html.twig', [            
            'exhibitions' => $exhibitions,        
        ]);
    }
}