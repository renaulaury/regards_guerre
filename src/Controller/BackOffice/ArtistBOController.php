<?php

namespace App\Controller\BackOffice;

use App\Entity\Artist;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface; 
use App\Form\BackOffice\ArtistAddEditBOType;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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

    /******************** Ajouter/modifier un artiste *********************/
    #[Route('/backOffice/artistAddBO', name: 'artistAddBO')]
    #[Route('/backOffice/artistEditBO/{slug}', name: 'artistEditBO')]
    public function artistAddEditBO(
        #[MapEntity(mapping: ['slug' => 'slug'])] ?Artist $artist = null,
        Request $request,         
        EntityManagerInterface $entityManager): Response
    {
        $isAdd = false; // Variable de contrôle

        // Déterminer si nous sommes en mode ajout ou édition
        if (!$artist) { //Si ?Artist n existe pas 
            $artist = new Artist(); // Créer une nouvelle instance pour l'ajout
            $isAdd = true; // Définir le mode ajout
        }

        // Création du form
        $form = $this->createForm(ArtistAddEditBOType::class, $artist);

        // Traiter la requête HTTP
        $form->handleRequest($request);

        // Vérif du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            //Générer le slug
            $slug = $artist->createSlugDateIdentityArtist();

            // Définir le slug sur l'entité
            $artist->setSlug($slug);
            
            // Persister les modifications dans la base de données
            $entityManager->persist($artist);
            $entityManager->flush();

            // Rediriger vers la liste des artistes
            return $this->redirectToRoute('artistListBO');
        }

        // Rendre le template avec le formulaire
        return $this->render('backOffice/artist/artistAddEditBO.html.twig', [
            'form' => $form->createView(),
            'artist' => $artist,
            'isAdd' => $isAdd, 
        ]);
    }

    /******************** Suppression un artiste *********************/
    //Demande de suppression
    #[Route('/backOffice/artistDeleteBO/{slug}', name: 'artistDeleteBO')]
    public function artistDeleteBO(#[MapEntity(mapping: ['slug' => 'slug'])] ?Artist $artist = null,): Response
    {       
        return $this->render('backOffice/artist/artistDeleteBO.html.twig', [            
            'artist' => $artist,
        ]);
    }

    //Confirmation de suppression
    #[Route('/backOffice/artistConfirmDeleteBO/{slug}', name: 'artistConfirmDeleteBO')]
    public function artistConfirmDeleteBO(
        #[MapEntity(mapping: ['slug' => 'slug'])] ?Artist $artist = null,
        EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'artiste est lié à une exposition
        if (!$artist->getShows()->isEmpty()) {
            // Ajouter un message flash pour avertir l'utilisateur
            $this->addFlash('error', 'ERREUR : Impossible de supprimer cet artiste car il est affilié à une ou plusieurs expositions.');
    
            // Rediriger l'utilisateur vers la liste des artistes ou une autre page
            return $this->redirectToRoute('artistListBO');
        }
    
        // Si l'artiste n'est pas lié à une exposition, le supprimer
        $entityManager->remove($artist);
        $entityManager->flush();
    
        // Ajouter un message flash pour confirmer la suppression
        $this->addFlash('success', 'SUCCES : L\'artiste a été supprimé avec succès.');
    
        return $this->redirectToRoute('artistListBO');
    }
}
