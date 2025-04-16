<?php

namespace App\Controller;


use App\Entity\Exhibition;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExhibitionController extends AbstractController
{
    /*Affiche la fiche complète de l'expo*/
    #[Route('/exhibition/{slug}', name: 'exhibition')]
    public function index(#[MapEntity(mapping: ['slug' => 'slug'])] ?Exhibition $exhibition = null): Response    
    {       
        
        return $this->render('/exhibition/index.html.twig', [
            'exhibition' => $exhibition,
        ]);
    }
}

 
