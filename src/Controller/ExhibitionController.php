<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ExhibitionController extends AbstractController
{
    #[Route('/exhibition', name: 'app_exhibition')]
    public function index(): Response
    {
        return $this->render('exhibition/index.html.twig', [
            'controller_name' => 'ExhibitionController',
        ]);
    }
}
