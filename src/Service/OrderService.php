<?php

namespace App\Service;

use Doctrine\Common\Collections\Collection;

class OrderService
{

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