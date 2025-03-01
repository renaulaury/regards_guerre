<?php

namespace App\Service;

use Twig\Environment;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrderExportService
{
    private PdfService $pdfService;
    private EmailService $emailService;
    private OrderRepository $orderRepository;
    private UrlGeneratorInterface $urlGenerator;
    private Environment $twig;
    private OrderService $orderService; 

    public function __construct(
        PdfService $pdfService,
        EmailService $emailService,
        OrderRepository $orderRepository,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        OrderService $orderService,
    ) {
        $this->pdfService = $pdfService;
        $this->emailService = $emailService;
        $this->orderRepository = $orderRepository;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
        $this->orderService = $orderService;
    }

/***************** Export d'une commande en pdf ***********************/   

public function exportOrder(int $orderId): RedirectResponse //use dans service
    {
        $order = $this->orderRepository->find($orderId);//id order
        $user = $order->getUser(); //id user
        $total = $this->orderService->orderTotal($order->getOrderDetails()); //total

        $pdfContent = $this->pdfService->generatePdf($order, ['total' => $total]); //pdf généré avec TOUT

       // Contenu -> template
       $body = $this->twig->render('emails/orderExportEmail.html.twig', [
        'pdfContent' => $pdfContent, //pdf
        'filename' => 'commande.pdf', //nom de fichier
        'user' => $user, 
        'order' => $order,
        'total' => $total, 
    ]);

    // Utilisation du service EmailService pour envoyer l'email
    $this->emailService->sendEmail(
        $user->getUserEmail(),
        'Votre commande', //Sujet
        $body, 
        $pdfContent,
        'commande.pdf'
    );

        return new RedirectResponse($this->urlGenerator->generate('userOrderBO', ['id' => $user->getId()]));
        //urlGenerator = redirectToRoute mais pour les services
    }







    
}