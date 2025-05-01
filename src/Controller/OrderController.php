<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\OrderConfirmationEmailService;
use App\Repository\OrderRepository;
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
        InvoiceService $orderHistoryService): Response
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

/***************** Envoi de la réservation en pdf ***********************/
    #[Route('/backOffice/user/userOrderExport/{orderId}', name: 'userOrderExport')]
    public function userOrderExport(
        int $orderId, 
        OrderConfirmationEmailService  $orderConfirmationEmailService ,
        OrderRepository $orderRepo): Response
    {
        
        $order = $orderRepo->find($orderId);

        if (!$order || !$order->getUser()) {
            $this->addFlash('error', 'Commande non trouvée.');
            return $this->redirectToRoute('index');
        }

        
        $orderConfirmationEmailService->sendOrderConfirmationEmailWithAttachments($orderId);
        
        
        $this->addFlash('success', 'Votre commande vous a été envoyée par mail.');
        return $this->redirectToRoute('orderHistory', ['slug' => $order->getUser()->getSlug()]);
    }
    
}
