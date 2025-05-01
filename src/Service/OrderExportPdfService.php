<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

/* Service pour exporter les pdf de commandes et de tickets */
/* Pdf Service */

class OrderExportPdfService
{
    private PdfService $pdfService;
    private OrderRepository $orderRepository;
    private EntityManagerInterface $entityManager;

    
    public function __construct(PdfService $pdfService, OrderRepository $orderRepository, EntityManagerInterface $entityManager)
    {
        $this->pdfService = $pdfService;
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
    }

    
/*************************** Pdf Facture ************************************/
    public function generateInvoicePdf(int $orderId): ?array
    {
        // Récupère l'entité Order à partir de son ID
        $order = $this->orderRepository->find($orderId);

        // Vérif si la cde existe et est associée à un utilisateur
        if (!$order || !$order->getUser()) {
            return null; 
        }

        // Récup les données nécessaires pour la génération du PDF de la facture
        $orderData = $this->getOrderDataForInvoicePdf($order);

        // Vérif si les données de la cde ont été récupérées avec succès
        if (!$orderData) {
            return null;
        }

        // Génère le contenu PDF (pdf service + template)
        $pdfContent = $this->pdfService->generatePdf(
            'pdf/orderPdf.html.twig',
            ['orderData' => $orderData]
        );

        // Retourne le contenu et le nom du fichier PDF
        return [
            'content' => $pdfContent,
            'filename' => 'facture_commande_' . $orderId . '.pdf',
        ];
    }

/*************************** Pdf etickets ************************************/
    public function generateTicketsPdf(int $orderId, array $groupedCart): ?array
    {
        // Récupère l'entité Order à partir de son ID
        $order = $this->orderRepository->find($orderId);

        // Vérifie si la commande existe et est associée à un utilisateur
        if (!$order || !$order->getUser()) {
            return null; // Retourne null si la commande n'est pas trouvée
        }

        $eTickets = [];

        // Vérifie si le panier regroupé contient des articles
        if (isset($groupedCart['items'])) {

            // Parcours les articles du panier regroupé
            foreach ($groupedCart['items'] as $item) {

                // Vérifie si l'article est un ticket et est associé à une exposition
                if ($item['type'] === 'ticket' && isset($item['exposition'])) {

                    // Génère le contenu PDF (pdf service + template)
                    $pdfContentTicket = $this->pdfService->generatePdf(
                        'pdf/eticketPdf.html.twig',
                        ['item' => $item, 'order' => $order]
                    );

                    // Add le contenu et le nom du fichier de l'e-ticket au tableau
                    $eTickets[] = [
                        'content' => $pdfContentTicket,
                        'filename' => 'e_ticket_' . $item['exposition']['slug'] . '_' . $orderId . '.pdf',
                    ];
                }
            }
        }

        // Retourne le tableau des e-tickets
        return $eTickets;
    }

    /*********************** permet de regrouper tout les éléments proprement de la commande ************************************/
    private function getOrderDataForInvoicePdf(Order $order): ?array
    {
        $groupedDetailsByExhibition = [];
        $totalOrder = 0; // Initialise le total de la commande

        // Parcours les détails de la cde
        foreach ($order->getOrderDetails() as $detail) {
            $exhibition = $detail->getExhibition();
            $ticket = $detail->getTicket();
            $quantity = $detail->getQuantity();
            $unitPrice = $detail->getUnitPrice();
            $exhibitionId = $exhibition->getId();

            // Regroupe les détails par expos
            if (!isset($groupedDetailsByExhibition[$exhibitionId])) {
                $groupedDetailsByExhibition[$exhibitionId] = [
                    'exhibition' => $exhibition,
                    'tickets' => []
                ];
            }

            // Add les informations du ticket à l'exposition correspondante
            $groupedDetailsByExhibition[$exhibitionId]['tickets'][] = [
                'ticket' => $ticket,
                'quantity' => $quantity,
                'price' => $unitPrice,
            ];

            // Calcule le total de la commande
            $totalOrder += $quantity * $unitPrice;
        }

        // Retourne un tableau pour la facture
        return [
            'order' => $order,
            'details' => $groupedDetailsByExhibition,
            'total' => $totalOrder,
        ];
    }
}