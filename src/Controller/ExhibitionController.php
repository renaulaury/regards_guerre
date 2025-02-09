<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Show;
use App\Entity\Artist;
use App\Entity\Exhibition;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExhibitionController extends AbstractController
{
    #[Route('/exhibition/{id}', name: 'exhibition')]
    public function index(Exhibition $exhibition, Artist $artist, Room $room, Show $shows): Response
    {
        
        // $showExhibit = $exhibitRepo->findAllInfosExhibition();

        return $this->render('/exhibition/index.html.twig', [
            'exhibition' => $exhibition,  
            'artist' => $artist,
            'room' => $room,
            'shows' => $shows,        
        ]);
    }
}

 
