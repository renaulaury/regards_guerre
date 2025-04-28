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
    #[Route('/backOffice/artistListBO/{filter?}', name: 'artistListBO')]
    public function artistListBO(
        ArtistBORepository $artistRepo,
        ?string $filter): Response
    {
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }
        
        $allArtists = $artistRepo->findArtists();
        $artistsNonAnonymized = [];
        $artistsAnonymized = [];

        foreach ($allArtists as $artist) {
            //Tous les artistes non anonymisés
            if (!$artist->isIsAnonymized() && $artist->getAnonymizeAt() === null) {
                $artistsNonAnonymized[] = $artist;

            //Artistes anonymisés avec et sans date
            } elseif ($artist->isIsAnonymized() || $artist->getAnonymizeAt() !== null) {
                $artistsAnonymized[] = $artist;
            }
        }

        // Filtres les artistes selon le filtre
        $artistsToDisplay = match ($filter) {
            'anonymized' => $artistsAnonymized,
            'artists' => $artistsNonAnonymized,
            default => $allArtists,
        };

        return $this->render('backOffice/artist/artistListBO.html.twig', [
            'artists' => $artistsToDisplay,
            'currentFilter' => $filter,
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
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

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
            //Générer le nouveau slug
            $slug = $artist->createSlugArtist();
            $artist->setSlug($slug);

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
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

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
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

        //Vérifier si l'artiste est lié à des expositions
        if ($artist->getShows()->isEmpty()) {
            // Si l'artiste n'est pas lié à une exposition -> suppr définitive
            $entityManager->remove($artist);
            $entityManager->flush();
            $this->addFlash('success', 'SUCCES : '. $artist .' a été supprimé avec succès.');

        } else { //Sinon anonymisation
            $latestDateExhibit = null; //Récup
            $now = new \DateTimeImmutable(); // Date du jour

            foreach ($artist->getShows() as $show) {
                $exhibition = $show->getExhibition();

                //Vérif si expo existe avt de récup la dernière date de l'artiste
                if ($exhibition && $exhibition->getDateExhibit() >= $now) {
                    if ($latestDateExhibit === null || $exhibition->getDateExhibit() > $latestDateExhibit) {
                        $latestDateExhibit = $exhibition->getDateExhibit();
                    }
                }
            }

            if ($latestDateExhibit) {
                // Si une exposition future existe, en attente -> planifier l'anonymisation
                $anonymizeAt = $latestDateExhibit->modify('+1 day');
                $artist->setAnonymizeAt($anonymizeAt);
                $artist->setIsAnonymized(false);
                $entityManager->persist($artist);
                $entityManager->flush();
                $this->addFlash('warning', 'INFO : L\'anonymisation de '. $artist . 'a été planifiée pour le ' . $anonymizeAt->format('d/m/Y'));
            } else {
                // Si toutes les expo liées sont antérieures à aujourd'hui -> anonymisation
                $artist->setArtistBirthDate(null);
                $artist->setArtistDeathDate(null);
                $artist->setArtistBio(null);
                $artist->setIsAnonymized(true);

                $entityManager->persist($artist);
                $entityManager->flush();
                $this->addFlash('success', 'SUCCES : '. $artist . 'a été anonymisé.');
            }
        }

        return $this->redirectToRoute('artistListBO');
    }
            
}
