<?php

namespace App\Controller\BackOffice;


use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\Share\ExhibitionShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class BackOfficeController extends AbstractController
{
    #[Route('/backOffice', name: 'backOffice')]
    public function index(
        ExhibitionShareRepository $exhibitionShareRepo, 
        Security $security): Response
    {
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

        $lastExhibitSoonSoldOut = null; //Stock d'alerte
        $lastExhibitSoldOut = null; //Stock épuisé
        
        $exhibitions = $exhibitionShareRepo->findAllNextExhibition();
        
        foreach ($exhibitions as $exhibition) {
            // Vérifie si le stock restant est inférieur ou égal au niveau d'alerte
            if ($exhibition->getTicketsRemaining() > 0 && $exhibition->getTicketsRemaining() <= $exhibition->getStockAlert()) {
                $lastExhibitSoonSoldOut = $exhibition; // Stocke le nom de l'exposition
            }
             // Vérifie si le stock restant est égal à zéro
            if ($exhibition->getTicketsRemaining() <= 0) {
                $lastExhibitSoldOut = $exhibition; 
            }        
        }
    
        
        // Ajoute un message flash si une alerte de stock ou un stock épuisé est détecté
        if ($security->isGranted('ROLE_ROOT') || $security->isGranted('ROLE_ADMIN')) {
            if ($lastExhibitSoonSoldOut) {
                $this->addFlash(
                    'warning',
                    'ATTENTION : Le seuil d\'alerte a été atteint pour l\'exposition ' . $lastExhibitSoonSoldOut . '.'
                );
            } 
            if ($lastExhibitSoldOut) {
                $this->addFlash(
                    'danger',
                    'URGENT : Le stock est épuisé pour l\'exposition : ' . $lastExhibitSoldOut . '.'
                );
            }
        }
        

        return $this->render('backOffice/index.html.twig', [
        ]);
    }

    
}
