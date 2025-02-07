<?php

namespace App\Controller;

use App\Entity\Exhibition;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExhibitionController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig', [
            'controller_name' => 'ExhibitionController',
        ]);
    }

    #[Route('/exhibition/{id}', name: 'exhibition')]
    public function index(Exhibition $exhibition): Response
    {
        return $this->render('exhibition/index.html.twig', [
            'exhibition' => $exhibition,
        ]);
    }
}
