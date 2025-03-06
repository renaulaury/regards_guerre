<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\OrderExportService;
use App\Service\OrderHistoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BackOffice\OrderBORepository;
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
    #[Route('/orderHistory/{id}', name: 'orderHistory')]
    
    public function orderHistory(User $user, OrderHistoryService $orderHistoryService): Response
    {

        $groupedOrders = $orderHistoryService->getUserOrderHistory($user);

        return $this->render('order/orderHistory.html.twig', [
            'groupedOrders' => $groupedOrders,
        ]);
    }

    /***************** Envoi de la réservation en pdf ***********************/
    #[Route('/backOffice/user/userOrderExportBO/{orderId}', name: 'userOrderExportBO')]
    public function userOrderExportBO(int $orderId, OrderExportService $orderExportService): Response
    {
        return $orderExportService->exportOrder($orderId);
    }
    
}
