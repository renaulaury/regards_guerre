<?php

namespace App\Controller;



use App\Entity\Order;
use App\Repository\ExhibitionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(ExhibitionRepository $exhibitRepo, Order $order, Request $request): Response
    {

        $exhibitions = $exhibitRepo->findNextExhibition(); //3 dernières expos programmées
        $agenda = $exhibitRepo->findAllNextExhibition(); //agenda des expos


        return $this->render('home.html.twig', [
            'exhibitions' => $exhibitions,
            'agenda' => $agenda,
        ]);
    }
    

}
