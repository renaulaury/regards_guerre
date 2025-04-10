<?php

namespace App\Controller\BackOffice;

use App\Entity\Show;
use App\Entity\Artist;
use App\Entity\Exhibition;
use App\Service\FileUploader;
use App\Service\ImageService;
use Intervention\Image\ImageManager;
use App\Form\BackOffice\ShowAddInfosBO;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BackOffice\ShowRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ShowController extends AbstractController
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    #[Route('/show', name: 'show')]
    public function index(): Response
    {
        return $this->render('show/index.html.twig', [
            'controller_name' => 'ShowController',
        ]);
    }


    /************************* Afficher détail de l'exposition *******************/

    //Détail de l'expo
    #[Route('/backOffice/exhibitShowBO/{id}', name: 'exhibitShowBO')]
    public function exhibitShowBO(Exhibition $exhibition, ExhibitionShareRepository $exhibitionShareRepo, EntityManagerInterface $entityManager, ShowRepository $showRepo): Response
    {
        //Récup tous les artistes associés à l'expo et ceux qui ne le sont pas
        $artists = $exhibitionShareRepo->findAll();
        $unPlanned = $exhibitionShareRepo->findUnplannedArtists($exhibition->getId());

        $forms = []; //Tabl pour les forms
        $shows = []; //Tableau pour infos artist du show

        // Récupère tous les artistes déjà associés à l'exposition
        $plannedArtists = $exhibition->getShows();

        // Pour chaque artiste déjà associé, crée ou récupère le formulaire et les infos du show
        foreach ($plannedArtists as $plannedArtist) {
            $show = $entityManager->getRepository(Show::class)->findOneBy([
                'exhibition' => $exhibition,
                'artist' => $plannedArtist->getArtist(),
            ]);

            if (!$show) {
                $show = new Show();
                $show->setExhibition($exhibition);
                $show->setArtist($plannedArtist->getArtist());
            }

            // Récupérer les IDs des salles déjà utilisées
            $showId = null;
            if ($show) {
                $showId = $show->getId();
            }
            $usedRoom = $showRepo->findUsedRoomInShow($exhibition->getId(), $showId);


            $form = $this->createForm(ShowAddInfosBO::class, $show, [
                'usedRoom' => $usedRoom,
            ]);
            $forms[$plannedArtist->getArtist()->getId()] = $form->createView();
            $shows[$plannedArtist->getArtist()->getId()] = $show;
        }

        //On boucle uniquement sur les artistes non planifiés
        foreach ($unPlanned as $unPlannedArtist) {
            $show = new Show(); //Création d'un nouveau show
            $show->setExhibition($exhibition); //Injection des infos de l'expo
            $show->setArtist($unPlannedArtist); //et de l'artiste

             // Récupérer les IDs des salles déjà utilisées
             $showId = null;
             if ($show) {
                 $showId = $show->getId();
             }
             $usedRoom = $showRepo->findUsedRoomInShow($exhibition->getId(), $showId);


            //Création du form pour les attributs de show
            $form = $this->createForm(ShowAddInfosBO::class, $show, [
                'usedRoom' => $usedRoom,
            ]);

            //Stocke la vue dans le tableau grâce à l'id
            $forms[$unPlannedArtist->getId()] = $form->createView();

            // Récup artist associé au show
             $show = $entityManager->getRepository(Show::class)->findOneBy([
                'exhibition' => $exhibition,
                'artist' => $unPlannedArtist,
            ]);
            
            $shows[$unPlannedArtist->getId()] = $show;
        }

        return $this->render('/backOffice/exhibition/exhibitShowBO.html.twig', [
            'exhibition' => $exhibition,
            'artists' => $artists,
            'unPlanned' => $unPlanned,
            'forms' => $forms,
            'shows' => $shows,
        ]);
    }

    //Ajout d'un show à l'expo suite à soumission du form
    #[Route('/backOffice/{idExhibit}/addArtistToExhibitBO/{idArtist}', name: 'addArtistToExhibitBO')]
    public function addArtistToExhibitBO(int $idExhibit, int $idArtist, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, ShowRepository $showRepo): Response
    {
        // Récupère l'exposition et l'artiste à partir de l'ID
        $exhibition = $entityManager->getRepository(Exhibition::class)->find($idExhibit);
        $artist = $entityManager->getRepository(Artist::class)->find($idArtist);
        
        // Vérifie si l'exposition et l'artiste existent.
        if ($exhibition && $artist) {
            
            // Vrifier si le show existe pour cette artiste et cette expo 
            $show = $entityManager->getRepository(Show::class)->findOneBy([
                'exhibition' => $exhibition,
                'artist' => $artist,
            ]);

            // Si le show n existe pas le créer
            if (!$show) {
                $show = new Show(); //Création d'un nouveau show
                $show->setExhibition($exhibition); //Injection des infos de lexpo
                $show->setArtist($artist); //et de l'artiste
            }    
            
            // Récup les IDs des salles déjà utilisées
            $showId = null; 
            if ($show) {
            $showId = $show->getId(); // Assignation si $show existe
            }

            $usedRoom = $showRepo->findUsedRoomInShow($exhibition->getId(), $showId);
            
            //Création du form pour les attributs de show
            $form = $this->createForm(ShowAddInfosBO::class, $show, [
                'usedRoom' => $usedRoom,
            ]);
            $form->handleRequest($request); // Traite la requête HTTP pour remplir le form

            if ($form->isSubmitted() && $form->isValid()) {
                /* Rechercher le dossier de l'expo */
                $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/images/events/' . $exhibition->getDateExhibit()->format('Ymd');

                /* Vérifier si l'image existe ou non et enregistrer l'image dans le dossier sous nom_prenom */
                $artistPhoto = $form->get('artistPhoto')->getData();

                if ($artistPhoto) {
                    // Définir le nom du fichier et le chemin d'enregistrement
                    $fileName = $artist->getArtistName() . '_' . $artist->getArtistFirstname();
                    $originalFilePath = $uploadDirectory . '/' . $fileName . '.' . $artistPhoto->guessExtension();
                    $artistPhoto->move($uploadDirectory, $fileName . '.' . $artistPhoto->guessExtension());

                    // Convertir en WebP
                    $webpFilePath = $uploadDirectory . '/' . $fileName . '.webp';
                    try {
                        $this->imageService->convertToWebP($originalFilePath, $webpFilePath);
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Erreur lors de la conversion de l\'image : ' . $e->getMessage());
                        return $this->redirectToRoute('exhibitShowBO', ['id' => $exhibition->getId()]);
                    }

                    // Supprimer le fichier original après conversion
                    unlink($originalFilePath);

                    // Mettre à jour le chemin de l'image dans l'entité
                    $show->setArtistPhoto('images/events/' . $exhibition->getDateExhibit()->format('Ymd') . '/' . $fileName . '.webp');
                }

                // Persister et enregistrer les modifications dans la base de données
                $entityManager->persist($show);
                $entityManager->flush();
                $this->addFlash('success', 'Artiste ajouté avec succès.');
            }

        }
        return $this->redirectToRoute('exhibitShowBO', ['id' => $exhibition->getId()]);
    }
    

    /***************** Suppression d'un artiste à l'expo ***********************/
    //Confirmation suppression artiste à l'expo
    #[Route('/backOffice/{idExhibit}/confirmRemoveArtistFromExhibitBO/{idArtist}', name: 'confirmRemoveArtistFromExhibitBO')]
    public function confirmRemoveArtistFromExhibitBO(int $idExhibit, int $idArtist, EntityManagerInterface $entityManager): Response
    {
        // Récupère l'exposition et l'artiste à partir de l'ID
        $exhibition = $entityManager->getRepository(Exhibition::class)->find($idExhibit);
        $artist = $entityManager->getRepository(Artist::class)->find($idArtist);
        
        return $this->render('backOffice/exhibition/confirmRemoveArtistFromExhibitBO.html.twig', [
            'exhibition' => $exhibition,
            'artist' => $artist,
        ]);
    }

    //Suppression de l'artiste
    #[Route('/backOffice/{idExhibit}/removeArtistFromExhibitBO/{idArtist}', name: 'removeArtistFromExhibitBO')]
    public function removeArtistFromExhibitBO(int $idExhibit, int $idArtist, EntityManagerInterface $entityManager): Response
    {
        //Récup expo et l'artite via leur id
        $exhibition = $entityManager->getRepository(Exhibition::class)->find($idExhibit);
        $artist = $entityManager->getRepository(Artist::class)->find($idArtist);


        //Vérifie si l'expo et l'artiste existent
        if ($exhibition && $artist) {
            $show = $entityManager->getRepository(Show::class)->findOneBy([
                'exhibition' => $exhibition,
                'artist' => $artist,
            ]);

            //Vérifie si le show existe
            if ($show) {
                $entityManager->remove($show); //Supprime le show
                $entityManager->flush(); //Enregistre les modifications
                $this->addFlash('success', 'Artiste supprimé avec succès.');
            } 

            return $this->redirectToRoute('exhibitShowBO', ['id' => $idExhibit]);
        }
        return $this->redirectToRoute('exhibitShowBO', ['id' => $idExhibit]);
    }
    
}
