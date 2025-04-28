<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Ticket>
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function findTicketDetails(int $ticketId): ?array
    {
        return $this->createQueryBuilder('t')
            ->select('e.titleExhibit AS exhibition, e.id AS exhibitionId, t.id AS ticketId, tp.standardPrice AS price')
            ->join('t.ticketPricings', 'tp')
            ->join('tp.exhibition', 'e')
            ->where('t.id = :ticketId')
            ->setParameter('ticketId', $ticketId)
            ->setMaxResults(1) //Récup 1 seul résultat répondant aux critères évitant les doublons
            ->getQuery()
            ->getOneOrNullResult();
    }

   
}



