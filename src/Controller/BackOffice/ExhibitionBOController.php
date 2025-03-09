<?php

namespace App\Controller\BackOffice;

use App\Entity\Exhibition;
use Doctrine\ORM\EntityManagerInterface; 
use App\Form\BackOffice\ExhibitAddEditBOType;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\Share\ExhibitionShareRepository; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExhibitionBOController extends AbstractController
{
    #[Route('/backOffice/exhibitListBO', name: 'exhibitListBO')]
    public function exhibitListBO(ExhibitionShareRepository $exhibitionShareRepo): Response
    {

        $exhibitions = $exhibitionShareRepo->findAllNextExhibition();

        return $this->render('/backOffice/exhibition/exhibitListBO.html.twig', [
            'exhibitions' => $exhibitions, 
        ]);
    }

    #[Route('/backOffice/exhibitAddBO', name: 'exhibitAddBO')]
    #[Route('/backOffice/exhibitEditBO/{id}', name: 'exhibitEditBO')]
    public function exhibitAddEditBO(Request $request, ?Exhibition $exhibition, EntityManagerInterface $entityManager): Response
    {
        $isAdd = false; // Variable de contrôle

        // Déterminer si nous sommes en mode ajout ou édition
        if (!$exhibition) { // Si l'exposition n'existe pas
            $exhibition = new Exhibition(); // Créer un nouveau formulaire
            $isAdd = true; // Définir le mode ajout
        }

        // Création du formulaire
        $form = $this->createForm(ExhibitAddEditBOType::class, $exhibition);

        // Traiter la requête HTTP
        $form->handleRequest($request);

        // Vérifier le formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister les modifications dans la base de données
            $entityManager->persist($exhibition);
            $entityManager->flush();

            // Rediriger vers la liste des expositions
            return $this->redirectToRoute('exhibitListBO');
        }

        // Rendre le template avec le formulaire
        return $this->render('backOffice/exhibition/exhibitAddEditBO.html.twig', [
            'form' => $form->createView(),
            'exhibition' => $exhibition,
            'isAdd' => $isAdd, 
        ]);
    }
}
