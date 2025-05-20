<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\InvoiceService;
use App\Repository\OrderRepository;
use App\Service\OrderHistoryService;
use App\Repository\InvoiceRepository;
use App\Service\OrderConfirmationEmailService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderController extends AbstractController
{
    
    /************* Affiche le récapitulatif de la commande ****************/
    #[Route('/order', name: 'index')]
    public function showCart(): Response
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
        // Vérif de l'accès
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('home');
        }

        $groupedOrders = $orderHistoryService->getUserOrderHistory($user->getId());

        return $this->render('order/orderHistory.html.twig', [
            'groupedOrders' => $groupedOrders,
        ]);
    }

/***************** Envoie de la commande en pdf ***********************/
#[Route('/profile/user/userInvoiceExport/{orderId}', name: 'userInvoiceExport')]
public function userInvoiceExport(
    int $orderId,
    OrderRepository $orderRepository,
    InvoiceRepository $invoiceRepository,
    InvoiceService $invoiceService
): Response {
    // Récupère l'entité Order à partir de l'ID.
    $order = $orderRepository->find($orderId);

    // Vérifie si la commande existe et est associée à un utilisateur.
    if (!$order || !$order->getUser()) {
        $this->addFlash('error', 'Commande non trouvée.');
        return $this->redirectToRoute('orderHistory', ['slug' => $order->getUser()->getSlug()]);
    }

    // Récupère l'entité Invoice associée à cette commande 
    $invoice = $invoiceRepository->findOneBy(['numberInvoice' => $order->getNumberInvoice()]);

    // Vérifie si la facture existe.
    if (!$invoice) {
        $this->addFlash('error', 'Facture non trouvée pour cette commande.');
        return $this->redirectToRoute('orderHistory', ['slug' => $order->getUser()->getSlug()]);
    }

    // Envoie l'email de la facture à l'utilisateur.
    $invoiceService->sendInvoiceEmail($invoice);

    $this->addFlash('success', 'La facture de votre commande N°' . $invoice->getNumberInvoice() . ' vous a été envoyée par mail.');
    return $this->redirectToRoute('orderHistory', ['slug' => $order->getUser()->getSlug()]);
}
    
}
