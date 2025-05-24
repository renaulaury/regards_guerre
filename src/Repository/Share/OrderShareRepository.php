<?php

namespace App\Repository\Share;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderShareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }


/************** Historique des commandes des utilisateurs  *********************/
    public function findOrdersDetailByUser(int $userId): array
    {
        // Récupération de l'EntityManager pour interagir avec la base de données
        $entityManager = $this->getEntityManager();
        // Création du QueryBuilder (spécifique Symfony) pour construire la requête DQL
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('o')
            ->from('App\Entity\Order', 'o')
            ->innerJoin('o.orderDetail', 'od')
            ->innerJoin('od.exhibition', 'e')
            ->innerJoin('od.ticket', 't')
            ->where('o.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('o.orderDateCreation', 'DESC');

        //Renvoie du résultat
        // getQuery() retourne l'objet Query Doctrine qui permet d'exécuter la requête construite
        // getResult() exécute la requête et retourne les résultats sous forme d'un tableau d'entités
        return $queryBuilder->getQuery()->getResult(); 
    }
}
