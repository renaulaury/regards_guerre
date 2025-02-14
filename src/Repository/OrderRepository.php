<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

//     public function showInfosCart() {

//         // Récupération de l'EntityManager pour interagir avec la base de données
//         $entityManager = $this->getEntityManager();
//         // Création du QueryBuilder (spécifique Symfony) pour construire la requête DQL
//         $queryBuilder = $entityManager->createQueryBuilder();

//         $queryBuilder->select( 't.id', 't.titleTicket', 'e.titleExhibit', 'tp.standardPrice')
//         ->from('App\Entity\Ticket', 't')
//         ->innerJoin('t.ticketPricings', 'tp') 
//         ->innerJoin('t.exhibition', 'e'); 

        
//        //Renvoie du résultat
//        // getQuery() retourne l'objet Query Doctrine qui permet d'exécuter la requête construite
//        // getResult() exécute la requête et retourne les résultats sous forme d'un tableau d'entités
//        return $queryBuilder->getQuery()->getResult();    
//    }
}
