<?php

namespace App\Controller\BackOffice;

use App\Entity\Show;
use App\Entity\Exhibition;
use App\Service\FileUploader;
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
                return $this->render('backOffice/exhibition/exhibitShowBO.html.twig', [
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

                // Validation du type MIME (Multipurpose Internet Mail Extensions)
                $allowedMimeTypes = ['image/jpeg', 'image/webp'];
                if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                    $this->addFlash('error', 'Veuillez télécharger une image valide (JPEG ou WebP).');

                    //redirige vers la page avec toute les infos d affichage
                    return $this->render('backOffice/exhibition/exhibitAddEditBO.html.twig', [
                        'form' => $form->createView(),
                        'exhibition' => $exhibition,
                        'isAdd' => $isAdd,
                        'dateExistsError' => $dateExistsError,
                    ]);
                }


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
            return $this->redirectToRoute('exhibitShowBO', ['id' => $exhibition->getId()]);
        }

        // Rendre le template avec le formulaire
        return $this->render('backOffice/exhibition/exhibitAddEditBO.html.twig', [
            'form' => $form->createView(),
            'exhibition' => $exhibition,
            'isAdd' => $isAdd,
            'dateExistsError' => $dateExistsError,
        ]);
    }

    /************************** Suppression d'une expo ******************************/
    #[Route('/backOffice/{idExhibit}/deleteExhibitBO', name: 'deleteExhibitBO')]
    public function deleteExhibitBO(int $idExhibit, EntityManagerInterface $entityManager): Response
    {
    // Récupère l'exposition à partir de l'ID
        $exhibition = $entityManager->getRepository(Exhibition::class)->find($idExhibit);        

        // Render the confirmation template
        return $this->render('backOffice/exhibition/exhibitConfirmDeleteBO.html.twig', [
            'exhibition' => $exhibition,            
        ]);
    }

    //Confirmation de suppression de l'expo
    #[Route('/backOffice/{idExhibit}/deleteConfirmExhibitBO', name: 'deleteConfirmExhibitBO')]
    public function deleteConfirmExhibitBO(int $idExhibit, EntityManagerInterface $entityManager, Filesystem $filesystem): Response
    {
        // Récupère l'exposition à partir de l'ID
        $exhibition = $entityManager->getRepository(Exhibition::class)->find($idExhibit);

        // Vérifie si l'exposition existe
        if ($exhibition) {
            // Supprime les shows associés à l'exposition
            $shows = $entityManager->getRepository(Show::class)->findBy(['exhibition' => $exhibition]);
            foreach ($shows as $show) {
                $entityManager->remove($show);
            }

            // Supprime les fichiers associés à l'exposition
            $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/images/events/' . $exhibition->getDateExhibit()->format('Ymd');
            if ($filesystem->exists($uploadDirectory)) {
                $filesystem->remove($uploadDirectory);
            }

            // Supprime l'exposition
            $entityManager->remove($exhibition);
            $entityManager->flush();

            $this->addFlash('success', 'Exposition supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Exposition non trouvée.');
        }

        return $this->redirectToRoute('exhibitListBO');
    }
    
}