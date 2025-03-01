<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OrderExportService
{
    private PdfService $pdfService;
    private EmailService $emailService;
    private OrderRepository $orderRepository;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        PdfService $pdfService,
        EmailService $emailService,
        OrderRepository $orderRepository,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->pdfService = $pdfService;
        $this->emailService = $emailService;
        $this->orderRepository = $orderRepository;
        $this->urlGenerator = $urlGenerator;
    }

/***************** Export d'une commande en pdf ***********************/   

public function exportOrder(int $orderId): RedirectResponse
    {
        $order = $this->orderRepository->find($orderId);//id order
        $user = $order->getUser(); //id user

        $pdfContent = $this->pdfService->generatePdf($order);

        // Utilisation du service EmailService pour envoyer l'email
        $this->emailService->sendEmail(
            $user->getUserEmail(),
            'Votre commande',
            '<p>Voici le PDF de votre commande en pièce jointe.</p>',
            $pdfContent,
            'commande.pdf'
        );

        return new RedirectResponse($this->urlGenerator->generate('userOrderBO', ['id' => $user->getId()]));
    }







    
}