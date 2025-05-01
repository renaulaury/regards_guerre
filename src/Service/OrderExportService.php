<?php

namespace App\Service;

use Twig\Environment;
use App\Repository\OrderRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderExportService
{
    private PdfService $pdfService;
    private EmailService $emailService;
    private OrderRepository $orderRepository;
    private Environment $twig;
    private OrderHistoryService $orderHistoryService; // Ajout du service OrderHistoryService

    public function __construct(
        PdfService $pdfService,
        EmailService $emailService,
        OrderRepository $orderRepository,
        Environment $twig,
        OrderHistoryService $orderHistoryService // Injection du nouveau service
    ) {
        $this->pdfService = $pdfService;
        $this->emailService = $emailService;
        $this->orderRepository = $orderRepository;
        $this->twig = $twig;
        $this->orderHistoryService = $orderHistoryService;
    }

    /***************** Export d'une commande en pdf ***********************/

    public function exportOrder(int $orderId): Void
    {
        $order = $this->orderRepository->find($orderId);
        
        if (!$order || !$order->getUser()) {
            throw new NotFoundHttpException(sprintf('Commande non trouvée (ID: %d)', $orderId));
        }
        
        $user = $order->getUser();
        $email = $user->getUserEmail();

        // Vérif rôle du user
        if (in_array('ROLE_DELETE', $user->getRoles(), true)) {
            $email = 'admin@regardsguerre.fr';
        }

        // Utilisation OrderHistoryService (regroupement commande)
        $groupedOrders = $this->orderHistoryService->getUserOrderHistory($user->getId());

        // Recherche de la commande spécifique dans le tableau regroupé
        $orderData = null;
        foreach ($groupedOrders as $groupedOrder) {
            if ($groupedOrder['order']->getId() === $order->getId()) { //Si id cde = id cde a exporter
                $orderData = $groupedOrder;
                break;
            }
        }
        
        // On génére le template de la facture
        $pdfContentInvoice = $this->pdfService->generatePdf(
            'pdf/orderPdf.html.twig', 
            ['orderData' => $orderData] // On passe les données de la facture 
        );

        // On génére le template de la commande 
        $pdfContentOrder = $this->pdfService->generatePdf(
            'pdf/etickets.html.twig', 
            ['orderData' => $orderData] // On passe les données de la commande 
        );

        // Préparations des pj pour l'email
        $attachments = [
            [
                'content' => $pdfContentInvoice,
                'filename' => 'facture.pdf',
            ],
            [
                'content' => $pdfContentOrder,
                'filename' => 'commande.pdf',
            ],
        ];
        

        //Envoie de l'email avec le pdf en pj (Facture + ????)
        $body = $this->twig->render('emails/orderExportEmail.html.twig', [ 
            'user' => $user,
            'groupedOrder' => $orderData, 
            'total' => $orderData['total'] ?? null, 
        ]);

        $this->emailService->sendEmail(
            $email,
            'Votre commande et votre facture',  
            $body,
            $attachments  // Tabl des pj
        );
    }
}