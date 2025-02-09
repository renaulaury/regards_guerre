<?php

namespace App\Controller;

use App\Entity\Show;
use App\Repository\ExhibitionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExhibitionController extends AbstractController
{
    #[Route('/exhibition/{id}', name: 'exhibition')]
    public function index(Show $show): Response
    {
        
        // $showExhibit = $exhibitRepo->findAllInfosExhibition();
       

        return $this->render('/exhibition/index.html.twig', [
            'show' => $show,          
        ]);
    }
}



// $title = $show->getExhibition();, Show $show   'title' => $title,  
