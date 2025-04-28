<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\Share\OrderShareRepository;

class OrderHistoryService
{
    private OrderShareRepository $orderShareRepository;
    private OrderService $orderService;

/******************** Permet le regroupement des commandes -> historique de cde -> Emission de pdf cde ****************************/
    public function __construct(OrderShareRepository $orderShareRepository, OrderService $orderService)
    {
        $this->orderShareRepository = $orderShareRepository;
        $this->orderService = $orderService;
    }

    /******************** Récup de l'historique de commande d'un utilisateur ****************************/
    public function getUserOrderHistory(int $userId): array
    {
        // Récupération des commandes de l'utilisateur
        $orders = $this->orderShareRepository->findOrdersDetailByUser($userId);

        // Initialisation du tableau pour les commandes regroupées
        $groupedOrders = [];

        // Parcours des commandes récupérées
        foreach ($orders as $order) {
            // Initialisation du tableau pour les détails de la commande regroupés par exposition
            $groupedDetailsByExhibition = [];

            // Calcul du total de la commande
            $totalOrder = $this->orderService->orderTotal($order->getOrderDetails());

            // Parcours des détails de la commande pour les regrouper par exposition
            foreach ($order->getOrderDetails() as $detail) {
                $exhibition = $detail->getExhibition();
                $ticket = $detail->getTicket();
                $quantity = $detail->getQuantity();
                $exhibitionId = $exhibition->getId();

                // Si l'exposition n'existe pas encore dans le tableau, on la crée
                if (!isset($groupedDetailsByExhibition[$exhibitionId])) {
                    $groupedDetailsByExhibition[$exhibitionId] = [
                        'exhibition' => $exhibition,
                        'tickets' => []
                    ];
                }

                // Ajout du ticket à la liste des tickets de l'exposition
                $groupedDetailsByExhibition[$exhibitionId]['tickets'][] = [
                    'ticket' => $ticket,
                    'quantity' => $quantity,
                    'price' => $detail->getUnitPrice(),
                ];
            }

            // Ajout de la commande complète et de ses détails regroupés
            $groupedOrders[] = [
                'order' => $order,
                'details' => $groupedDetailsByExhibition,
                'total' => $totalOrder,
            ];
        }

        return $groupedOrders;
    }
}