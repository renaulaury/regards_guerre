<?php

namespace App\Controller\BackOffice;

use App\Entity\User;
use App\Service\OrderService;
use App\Service\OrderExportService;
use App\Service\OrderHistoryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderBOController extends AbstractController

{    
   
/***************** Historique d'un user ***********************/
/*********** Affiche l'historique de commande de l'utilisateur ************************/
#[Route('/backOffice/user/userOrderBO/{slug}', name: 'userOrderBO')]        
public function userOrderBO(
    #[MapEntity(mapping: ['slug' => 'slug'])] ?User $user = null, 
    OrderHistoryService $orderHistoryService): Response
{
    // Vérif de l'accès
    if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
        return $this->redirectToRoute('home');
    }

    $groupedOrders = $orderHistoryService->getUserOrderHistory($user->getId());

    return $this->render('backOffice/user/userOrderBO.html.twig', [
        'groupedOrders' => $groupedOrders,
        'user'  => $user,
    ]);
}



/***************** Envoi de la réservation en pdf ***********************/
    #[Route('/backOffice/user/userOrderExportBO/{orderId}', name: 'userOrderExportBO')]
    public function userOrderExportBO(
        int $orderId, 
        OrderExportService $orderExportService,
        OrderService $orderService): Response
    {
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

        $orderExportService->exportOrder($orderId);
        
        $order = $orderService->findOrder($orderId); 

    if ($order && $order->getUser()) {
        return $this->redirectToRoute('userOrderBO', ['slug' => $order->getUser()->getSlug()]);
    } else {
    
        return $this->redirectToRoute('backOffice/user/userListBO.html.twig');
    }
    
    }

    
}
