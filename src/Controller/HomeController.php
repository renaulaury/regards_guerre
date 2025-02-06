<?php

namespace App\Controller;


use App\Entity\Exhibition;
use App\Repository\ExhibitionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(ExhibitionRepository $exhibitRepo): Response
    {

        $exhibitions = $exhibitRepo->findNextExhibition();

        return $this->render('home.html.twig', [
            'exhibitions' => $exhibitions,
        ]);
    }
}
