<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
           ->from('App\Entity\ticketPricing', 'tp')
           ->innerJoin('tp.exhibition', 'e')
           ->innerJoin('tp.ticket', 't');

         //Renvoie du résultat
       // getQuery() retourne l'objet Query Doctrine qui permet d'exécuter la requête construite
       // getResult() exécute la requête et retourne les résultats sous forme d'un tableau d'entités
       return $queryBuilder->getQuery()->getResult();
   }


   
}
