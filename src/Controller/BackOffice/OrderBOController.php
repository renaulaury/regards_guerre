<?php

namespace App\Controller\BackOffice;

use App\Entity\User;
use App\Service\InvoiceService;
use App\Repository\OrderRepository;
use App\Service\OrderHistoryService;
use App\Repository\InvoiceRepository;
use App\Service\OrderExportPdfService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderBOController extends AbstractController

{    
   
/***************** Historique d'un user ***********************/
/*********** Affiche l'historique de commandes de l'utilisateur ************************/
#[Route('/backOffice/user/userOrderBO/{slug}', name: 'userOrderBO')]        
public function userOrderBO(    
    OrderHistoryService $orderHistoryService,
    #[MapEntity(mapping: ['slug' => 'slug'])] ?User $user = null): Response
{
    // Vérif de l'accès
    if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
        return $this->redirectToRoute('home');
    }

    $groupedOrders = $orderHistoryService->getUserOrderHistory($user->getId());

    return $this->render('backOffice/user/userOrderBO.html.twig', [
        'groupedOrders' => $groupedOrders,
        'user'  => $user,
    ]);
}



/***************** Envoi de la commande en pdf ***********************/
    #[Route('/backOffice/user/userOrderExportBO/{orderId}', name: 'userInvoiceExportBO')]
    public function userInvoiceExportBO(
        int $orderId,
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository,
        InvoiceService $invoiceService): Response
    {
        // Vérif de l'accès
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_ROOT')) {
            return $this->redirectToRoute('home');
        }

        // Récupère l'entité Order à partir de l'ID.
        $order = $orderRepository->find($orderId);

        // Vérifie si la commande existe et est associée à un utilisateur.
        if (!$order || !$order->getUser()) {
            $this->addFlash('error', 'Commande non trouvée.');
            return $this->redirectToRoute('userOrderBO', ['slug' => $order->getUser()->getSlug()]);
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

        $this->addFlash('success', 'La facture de votre commande N°' . $invoice->getNumberInvoice() . ' a été envoyée par mail à l\'utilisateur.');
        return $this->redirectToRoute('userOrderBO', ['slug' => $order->getUser()->getSlug()]);
    
    }

    
}
