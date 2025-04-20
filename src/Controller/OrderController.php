<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\OrderExportService;
use App\Service\OrderHistoryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderController extends AbstractController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    
    /************* Affiche le récapitulatif de la commande ****************/
    #[Route('/order', name: 'index')]
    public function showCart(CartService $cartService): Response
    {
        return $this->render('order/index.html.twig', [
        ]);
    }
    
/*********** Affiche l'historique de commande de l'utilisateur ************************/
    #[Route('/orderHistory/{slug}', name: 'orderHistory')]
    
    public function orderHistory(
        #[MapEntity(mapping: ['slug' => 'slug'])] ?User $user,
        OrderHistoryService $orderHistoryService): Response
    {

        $groupedOrders = $orderHistoryService->getUserOrderHistory($user->getId());

        return $this->render('order/orderHistory.html.twig', [
            'groupedOrders' => $groupedOrders,
        ]);
    }

/***************** Envoi de la réservation en pdf ***********************/
    #[Route('/backOffice/user/userOrderExport/{orderId}', name: 'userOrderExport')]
    public function userOrderExport(
        int $orderId, 
        OrderExportService $orderExportService,
        OrderService $orderService): Response
    {
        $orderExportService->exportOrder($orderId);
        
        $order = $orderService->findOrder($orderId); 

    if ($order && $order->getUser()) {
        return $this->redirectToRoute('orderHistory', ['slug' => $order->getUser()->getSlug()]);
    } else {
    
        return $this->redirectToRoute('index');
    }
    }
    
}
