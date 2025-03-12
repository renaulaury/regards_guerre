<?php

namespace App\Controller\BackOffice;

use App\Entity\Show;
use App\Entity\Artist;
use App\Entity\Exhibition;
use App\Service\FileUploader;
use App\Form\BackOffice\ShowAddInfosBO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Filesystem\Filesystem;
use App\Form\BackOffice\ExhibitAddEditBOType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExhibitionBOController extends AbstractController
{
    /******************* Liste des expositions  *************************/
    #[Route('/backOffice/exhibitListBO', name: 'exhibitListBO')]
    public function exhibitListBO(ExhibitionShareRepository $exhibitionShareRepo): Response
    {
        $exhibitions = $exhibitionShareRepo->findAllNextExhibition();

        return $this->render('/backOffice/exhibition/exhibitListBO.html.twig', [
            'exhibitions' => $exhibitions,
        ]);
    }

    /******************* Ajout et édition d'une exposition  *************************/
    #[Route('/backOffice/exhibitAddBO', name: 'exhibitAddBO')]
    #[Route('/backOffice/exhibitEditBO/{id}', name: 'exhibitEditBO')]
    public function addEditExhibitBO(Request $request, ?Exhibition $exhibition, EntityManagerInterface $entityManager, FileUploader $fileUploader, Filesystem $filesystem, Security $security): Response
    {
        $isAdd = false; // Variable de contrôle
        $oldDate = null; // Variable de contrôle - date de dossier
        $dateExistsError = false; // Variable pour indiquer une erreur de date existante

        // Déterminer si nous sommes en mode ajout ou édition
        if (!$exhibition) { // Si l'exposition n'existe pas
            $exhibition = new Exhibition(); // Créer un nouveau formulaire
            $isAdd = true; // Définir le mode ajout
        } else {
            $oldDate = $exhibition->getDateExhibit(); // Stocker l'ancienne date de dossier
        }

        // Création du formulaire
        $form = $this->createForm(ExhibitAddEditBOType::class, $exhibition);

        // Traiter la requête HTTP
        $form->handleRequest($request);

        // Vérifier le formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Création du dossier avec la date de l'exposition
            $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/images/events/' . $exhibition->getDateExhibit()->format('Ymd');

            // Vérifier si le dossier existe déjà
            if ($filesystem->exists($uploadDirectory)) {
                $dateExistsError = true;
                $this->addFlash('error', 'Une exposition avec cette date existe déjà.');

                // Rendre le template avec le formulaire et le message d'erreur
                return $this->render('backOffice/exhibition/exhibitDetailBO.html.twig', [
                    'form' => $form->createView(),
                    'exhibition' => $exhibition,
                    'isAdd' => $isAdd,
                    'dateExistsError' => $dateExistsError,
                ]);
            }

            $filesystem->mkdir($uploadDirectory); // Création répertoire de destination

            // Handle file upload
            $file = $form->get('mainImage')->getData();
            if ($file) { // Si un fichier a été envoyé alors l'enregistrer
                // Enregistrer l'image principale avec le nom 00_main_image
                $fileName = '00_main_image.' . $file->guessExtension(); // Crée le nom du fichier + extension
                $fileUploader->upload($file, $uploadDirectory, $fileName); // Dl le fichier vers le répertoire

                // Mettre à jour le chemin de l'image dans l'entité
                $exhibition->setMainImage('images/events/' . $exhibition->getDateExhibit()->format('Ymd') . '/' . $fileName);

                // Renommer le dossier si la date a été modifiée
                if ($oldDate && $oldDate != $exhibition->getDateExhibit()) {
                    $oldDirectory = $this->getParameter('kernel.project_dir') . '/public/images/events/' . $oldDate->format('Ymd');

                    if ($filesystem->exists($oldDirectory)) {
                        $filesystem->rename($oldDirectory, $uploadDirectory);
                    }
                }
            }

            // Récupérer l'utilisateur connecté
            $user = $security->getUser();

            // Associer l'utilisateur à l'exposition
            $exhibition->setUser($user);

            // Persister les modifications dans la base de données
            $entityManager->persist($exhibition);
            $entityManager->flush();

            // Rediriger vers la liste des expositions
            return $this->redirectToRoute('exhibitDetailBO');
        }

        // Rendre le template avec le formulaire
        return $this->render('backOffice/exhibition/exhibitAddEditBO.html.twig', [
            'form' => $form->createView(),
            'exhibition' => $exhibition,
            'isAdd' => $isAdd,
            'dateExistsError' => $dateExistsError,
        ]);
    }

    /************************* Afficher détail de l'exposition *******************/

    //Détail de l'expo
    #[Route('/backOffice/exhibitDetailBO/{id}', name: 'exhibitDetailBO')]
    public function exhibitDetailBO(Exhibition $exhibition, ExhibitionShareRepository $exhibitionShareRepo): Response
    {
        //Récup tous les artistes associés à l'expo et ceux qui ne le sont pas
        $artists = $exhibitionShareRepo->findAll();
        $unPlanned = $exhibitionShareRepo->findUnplannedArtists($exhibition->getId());

        $forms = []; //Tabl pour les forms

        //On boucle uniquement sur les artistes non planifiés
        foreach ($unPlanned as $unPlannedArtist) {
            $show = new Show(); //Création d'un nouveau show
            $show->setExhibition($exhibition); //Injection des infos de l'expo
            $show->setArtist($unPlannedArtist); //et de l'artiste

            //Création du form pour les attributs de show
            $form = $this->createForm(ShowAddInfosBO::class, $show);

            //Stocke la vue dans le tableau grâce à l'id
            $forms[$unPlannedArtist->getId()] = $form->createView();
        }

        return $this->render('/backOffice/exhibition/exhibitDetailBO.html.twig', [
            'exhibition' => $exhibition,
            'artists' => $artists,
            'unPlanned' => $unPlanned,
            'forms' => $forms,
        ]);
    }

    //Ajout d'un show à l'expo suite à soumission du form
    #[Route('/backOffice/{idExhibit}/addArtistToExhibitBO/{idArtist}', name: 'addArtistToExhibitBO')]
    public function addArtistToExhibitBO(int $idExhibit, int $idArtist, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupère l'exposition et l'artiste à partir de l'ID
        $exhibition = $entityManager->getRepository(Exhibition::class)->find($idExhibit);
        $artist = $entityManager->getRepository(Artist::class)->find($idArtist);
        
        // Vérifie si l'exposition et l'artiste existent.
        if ($exhibition && $artist) {
            
            $show = new Show(); //Création d'un nouveau show
            $show->setExhibition($exhibition); //Injection des infos de lexpo
            $show->setArtist($artist); //et de l'artiste

            //Création du form pour les attributs de show
            $form = $this->createForm(ShowAddInfosBO::class, $show);
            $form->handleRequest($request); // Traite la requête HTTP pour remplir le form

            if ($form->isSubmitted() && $form->isValid()) {
                // Persister et enregistre les modifications dans la base de données
                $entityManager->persist($show);
                $entityManager->flush();
                $this->addFlash('success', 'Artiste ajouté avec succès.');
            }

        }
        return $this->redirectToRoute('exhibitDetailBO', ['id' => $exhibition->getId()]);
    }

    //Suppression d'un artiste à l'expo 
    #[Route('/backOffice/{idExhibit}/removeArtistFromExhibitBO/{idArtist}', name: 'removeArtistFromExhibitBO')]
    public function removeArtistFromExhibitBO(int $idExhibit, int $idArtist, EntityManagerInterface $entityManager): Response
    {
        $exhibition = $entityManager->getRepository(Exhibition::class)->find($idExhibit);
        $artist = $entityManager->getRepository(Artist::class)->find($idArtist);

        if ($exhibition && $artist) {
            $show = $entityManager->getRepository(Show::class)->findOneBy([
                'exhibition' => $exhibition,
                'artist' => $artist,
            ]);

            if ($show) {
                $entityManager->remove($show);
                $entityManager->flush();
                $this->addFlash('success', 'Artiste supprimé avec succès.');
            } 

            return $this->redirectToRoute('exhibitDetailBO', ['id' => $idExhibit]);
        }
    }
}