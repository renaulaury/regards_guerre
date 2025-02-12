<?php

namespace App\Repository;


use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllProductsByExhibition() {
        // Récupération de l'EntityManager pour interagir avec la base de données
        $entityManager = $this->getEntityManager();
        // Création du QueryBuilder (spécifique Symfony) pour construire la requête DQL
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('DISTINCT e.id, e.titleExhibit', 'e.dateExhibit', 't.id AS productId', 't.titleproduct', 't.imageproduct', 't.imageproductAlt', 'pp.standardPrice')
           ->from('App\Entity\productPricing', 'pp')
           ->innerJoin('tp.exhibition', 'e')
           ->innerJoin('tp.product', 't');

         //Renvoie du résultat
       // getQuery() retourne l'objet Query Doctrine qui permet d'exécuter la requête construite
       // getResult() exécute la requête et retourne les résultats sous forme d'un tableau d'entités
       return $queryBuilder->getQuery()->getResult();
   }

   
}
