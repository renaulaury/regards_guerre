<?php

namespace App\Controller\BackOffice;

use App\Entity\Artist;
use App\Form\BackOffice\ArtistEditBOType;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BackOffice\ArtistBORepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ArtistBOController extends AbstractController
{
    /******************** Affichage liste des artistes *********************/
    #[Route('/backOffice/artistListBO', name: 'artistListBO')]
    public function artistListBO(ArtistBORepository $artistRepo): Response
    {
        $artists = $artistRepo->findArtists();

        return $this->render('backOffice/artist/artistListBO.html.twig', [
            'artists' => $artists,
        ]);
    }

    /******************** Modifier un artiste *********************/
    #[Route('/backOffice/artistEditBO/{id}', name: 'artistEditBO')]
    public function artistEditBO(Artist $artist, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création du form
        $form = $this->createForm(ArtistEditBOType::class, $artist, ['entityManager' => $entityManager]);

        // Traiter la requête HTTP
        $form->handleRequest($request);

        // Vérif du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister les modifications dans la base de données
            $entityManager->flush();

            // Rediriger vers la liste des artistes
            return $this->redirectToRoute('artistListBO');
        }

        // Rendre le template avec le formulaire
        return $this->render('backOffice/artist/artistEditBO.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
