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

    public function findAllTicketsByExhibition() {
        // Récupération de l'EntityManager pour interagir avec la base de données
        $entityManager = $this->getEntityManager();
        // Création du QueryBuilder (spécifique Symfony) pour construire la requête DQL
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('DISTINCT e.id, e.titleExhibit', 'e.dateExhibit', 't.id AS ticketId', 't.titleTicket', 't.imageTicket', 't.imageTicketAlt', 'tp.standardPrice')
           ->from('App\Entity\TicketPricing', 'tp')
           ->innerJoin('tp.exhibition', 'e')
           ->innerJoin('tp.ticket', 't');

         //Renvoie du résultat
       // getQuery() retourne l'objet Query Doctrine qui permet d'exécuter la requête construite
       // getResult() exécute la requête et retourne les résultats sous forme d'un tableau d'entités
       return $queryBuilder->getQuery()->getResult();
   }

//    public function findTicketWithPrice($ticketId) {

//     // return $this->createQueryBuilder('t')
//     // ->leftJoin('t.ticketPricings', 'tp')
//     // ->leftJoin('tp.exhibition', 'e')
//     // ->addSelect('tp', 'e')
//     // ->where('t.id = :ticketId')
//     // ->setParameter('ticketId', $ticketId)
//     // ->getQuery()
//     // ->getOneOrNullResult();
//     $qb = $this->createQueryBuilder('t')
//         ->leftJoin('t.ticketPricings', 'tp') // Jointure avec TicketPricing
//         ->addSelect('tp') // Sélectionner également les entités Exhibition et TicketPricing
//         ->where('t.id = :ticketId') // Condition pour filtrer par l'ID du Ticket
//         ->setParameter('ticketId', $ticketId);

//         return $qb->getQuery()->getOneOrNullResult();
// }  
public function findTicketDetails(int $ticketId): ?array
{
    return $this->createQueryBuilder('t')
        ->select('e.titleExhibit AS exhibition, e.id AS exhibitionId, t.id AS ticketId, tp.standardPrice AS price')
        ->join('t.ticketPricings', 'tp')
        ->join('tp.exhibition', 'e')
        ->where('t.id = :ticketId')
        ->setParameter('ticketId', $ticketId)
        ->getQuery()
        ->getOneOrNullResult();
}

   
}

   

