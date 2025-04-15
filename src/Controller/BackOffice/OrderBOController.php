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
    private OrderService $orderService; 

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
   
/***************** Historique d'un user ***********************/
/*********** Affiche l'historique de commande de l'utilisateur ************************/
#[Route('/backOffice/user/userOrderBO/{slug}', name: 'userOrderBO')]        
public function userOrderBO(
    #[MapEntity(mapping: ['slug' => 'slug'])] ?User $user = null,
    OrderHistoryService $orderHistoryService): Response
{

    $groupedOrders = $orderHistoryService->getUserOrderHistory($user);

    return $this->render('backOffice/user/userOrderBO.html.twig', [
        'groupedOrders' => $groupedOrders,
        'user'  => $user,
    ]);
}


/***************** Envoi de la réservation en pdf ***********************/
    #[Route('/backOffice/user/userOrderExportBO/{orderId}', name: 'userOrderExportBO')]
    public function userOrderExportBO(
        int $orderId, 
        OrderExportService $orderExportService): Response
    {
        return $orderExportService->exportOrder($orderId);
    }

    
}
