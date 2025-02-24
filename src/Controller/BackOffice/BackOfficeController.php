<?php

namespace App\Controller\BackOffice;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class BackOfficeController extends AbstractController
{
    #[Route('/backOffice', name: 'backOffice')]
    public function index(): Response
    {
        return $this->render('backOffice/index.html.twig', [
        ]);
    }

    
}
