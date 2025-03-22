<?php

namespace App\Controller;


use App\Entity\Exhibition;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExhibitionController extends AbstractController
{
    /*Affiche la fiche complète de l'expo*/
    #[Route('/exhibition/{id}', name: 'exhibition')]
    public function index(Exhibition $exhibition): Response
    {

        return $this->render('/exhibition/index.html.twig', [
            'exhibition' => $exhibition, 
        ]);
    }
}

 
