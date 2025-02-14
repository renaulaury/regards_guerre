<?php

namespace App\Repository;

use App\Entity\TicketPricing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TicketPricing>
 */
class TicketPricingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketPricing::class);
    }

    // public function findPriceByTicket($ticketId) {

    //     return $this->createQueryBuilder('tp')
    //     ->innerJoin('tp.ticket', 't')
    //     ->innerJoin('tp.exhibition', 'e')
    //     ->addSelect('t', 'e')
    //     ->where('t.id = :ticketId')
    //     ->setParameter('ticketId', $ticketId)
    //     ->getQuery()
    //     ->getOneOrNullResult();
    // }   
}
