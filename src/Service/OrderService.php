<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Collection;

class OrderService
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    

/************** Calcul du total de la commande ***********************/
    public function orderTotal(Collection $orderDetails): float
    {
        $total = 0;
        foreach ($orderDetails as $detail) {
            $total += $detail->getUnitPrice() * $detail->getQuantity();
        }
        return $total;
    }


}