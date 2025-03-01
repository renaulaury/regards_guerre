<?php

namespace App\Controller\BackOffice;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BackOffice\OrderBORepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\OrderService;
use Symfony\Component\Mailer\MailerInterface;

final class OrderBOController extends AbstractController

{    
    private $orderService;    
    private MailerInterface $mailer;

    public function __construct(OrderService $orderService, MailerInterface $mailer)
    {
        $this->orderService = $orderService;
        $this->mailer = $mailer;
    }
   
/***************** Historique d'un user ***********************/
    #[Route('/backOffice/user/userOrderBO/{id}', name: 'userOrderBO')]
    public function userOrderBO(User $user, OrderBORepository $orderBORepo): Response
    {
         // Récup des commandes de l'utilisateur
         $orders = $orderBORepo->findOrdersDetailByUser($user->getId());

         // Init du tableau pour les commandes regroupées
         $groupedOrders = [];
 
         // Parcours des commandes récupérées
         foreach ($orders as $order) {
             // Init du tableau pour la commande regroupée
             $groupedDetails = [];
 
             // Parcours des détails de la commande 
             foreach ($order->getOrderDetails() as $detail) {
                 // Récupération de l'identifiant unique de l'exposition
                 $exhibitionId = $detail->getExhibition()->getId();
                 // Récupération de l'identifiant unique du ticket
                 $ticketId = $detail->getTicket()->getId();
 
                 //Calcul du total de la commande
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
 
         
         return $this->render('backOffice/user/userOrderBO.html.twig', [
             'user' => $user,
             'groupedOrders' => $groupedOrders,
         ]);
    }

    #[Route('/backOffice/user/userOrderExportBO/{id}', name: 'userOrderExportBO')]
    
    public function userOrderExportBO(): Response
    {

        return $this->render('backOffice/user/userOrderBO.html.twig', [
            
        ]);
   }
}
