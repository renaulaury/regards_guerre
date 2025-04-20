<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\Share\OrderShareRepository;

class OrderHistoryService
{
    private OrderShareRepository $orderShareRepository;
    private OrderService $orderService;

    public function __construct(OrderShareRepository $orderShareRepository, OrderService $orderService)
    {
        $this->orderShareRepository = $orderShareRepository;
        $this->orderService = $orderService;
    }

    public function getUserOrderHistory(int $userId): array
    {
        // Récupération des commandes de l'utilisateur
        $orders = $this->orderShareRepository->findOrdersDetailByUser($userId);

        // Initialisation du tableau pour les commandes regroupées
        $groupedOrders = [];

        // Parcours des commandes récupérées
        foreach ($orders as $order) {
            // Initialisation du tableau pour la commande regroupée
            $groupedDetails = [];

            // Parcours des détails de la commande
            foreach ($order->getOrderDetails() as $detail) {
                // Récupération de l'identifiant unique de l'exposition
                $exhibitionId = $detail->getExhibition()->getId();
                // Récupération de l'identifiant unique du ticket
                $ticketId = $detail->getTicket()->getId();

                // Calcul du total de la commande
                $totalOrder = $this->orderService->orderTotal($order->getOrderDetails());

                // Regroupement des tickets par exposition
                if (!isset($groupedDetails[$exhibitionId])) {
                    // Si l'exposition n'existe pas encore dans le tableau, on la crée
                    $groupedDetails[$exhibitionId] = [
                        'exhibition' => $detail->getExhibition(),
                        'tickets' => []
                    ];
                }

                // Regroupement des quantités de tickets identiques par exposition
                if (!isset($groupedDetails[$exhibitionId]['tickets'][$ticketId])) {
                    // Si le ticket n'existe pas encore pour cette exposition, on le crée
                    $groupedDetails[$exhibitionId]['tickets'][$ticketId] = [
                        'ticket' => $detail->getTicket(),
                        'quantity' => $detail->getQuantity(),
                        'price' => $detail->getUnitPrice(),
                    ];
                } else {
                    // Sinon, on incrémente la quantité du ticket existant
                    $groupedDetails[$exhibitionId]['tickets'][$ticketId]['quantity'] += $detail->getQuantity();
                }
            }
            // Ajout de la commande complète
            $groupedOrders[] = [
                'order' => $order,
                'details' => $groupedDetails,
                'total' => $totalOrder,
            ];
        }

        return $groupedOrders;
    }
}