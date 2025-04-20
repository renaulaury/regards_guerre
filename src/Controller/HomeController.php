<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

final class HomeController extends AbstractController
{
    
/************** Affiche le carrousel + l'agenda *********************/ 
    #[Route('/home', name: 'home')]
    public function index(ExhibitionShareRepository $exhibitShareRepo): Response
    {
    
        $exhibitions = $exhibitShareRepo->findNextExhibition(); //3 dernières expos programmées
        $agenda = $exhibitShareRepo->findAllNextExhibition(); //agenda des expos


        return $this->render('home.html.twig', [
            'exhibitions' => $exhibitions,
            'agenda' => $agenda,
        ]);
    }

    #[Route('/privacyPolicy', name: 'privacyPolicy')]
    public function privacyPolice(): Response
    {

        return $this->render('legalTexts/privacyPolicy.html.twig', [
        ]);
    }

    #[Route('/legalNotices', name: 'legalNotices')]
    public function legalNotices(): Response
    {
    
        return $this->render('legalTexts/legalNotices.html.twig', [
        ]);
    }

    

}
