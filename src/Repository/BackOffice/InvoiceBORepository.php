<?php

namespace App\Repository\BackOffice;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 */
class InvoiceBORepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    /************** Liste totale des factures ********************/
    public function findInvoices() {
        // Récupération de l'EntityManager pour interagir avec la base de données
        $entityManager = $this->getEntityManager();
        // Création du QueryBuilder (spécifique Symfony) pour construire la requête DQL
        $queryBuilder = $entityManager->createQueryBuilder();
       

        $queryBuilder->select('i')
            ->from('App\Entity\Invoice', 'i')
            ->orderBy('i.dateInvoice', 'ASC') // Ordre chronologique sur la date de la facture
            ->addOrderBy('i.customerName', 'ASC'); // Ordre alphabétique sur le nom

        //Renvoie du résultat
        // getQuery() retourne l'objet Query Doctrine qui permet d'exécuter la requête construite
        // getResult() exécute la requête et retourne les résultats sous forme d'un tableau d'entités
        return $queryBuilder->getQuery()->getResult(); 
    }

    /************** Liste total des factures par utilisateur ********************/
    public function findInvoicesByName() {
        // Récupération de l'EntityManager pour interagir avec la base de données
        $entityManager = $this->getEntityManager();
        // Création du QueryBuilder (spécifique Symfony) pour construire la requête DQL
        $queryBuilder = $entityManager->createQueryBuilder();
       

        $queryBuilder->select('i')
            ->from('App\Entity\Invoice', 'i')
            ->addOrderBy('i.customerName', 'ASC'); // Ordre alphabétique sur le nom

        //Renvoie du résultat
        // getQuery() retourne l'objet Query Doctrine qui permet d'exécuter la requête construite
        // getResult() exécute la requête et retourne les résultats sous forme d'un tableau d'entités
        return $queryBuilder->getQuery()->getResult(); 
    }

    /************** Liste total des factures par date ********************/
    public function findInvoicesByYear(int $year) {
        // Récupération de l'EntityManager pour interagir avec la base de données
        $entityManager = $this->getEntityManager();
        // Création du QueryBuilder (spécifique Symfony) pour construire la requête DQL
        $queryBuilder = $entityManager->createQueryBuilder();
       

        $queryBuilder->select('i')
            ->from('App\Entity\Invoice', 'i')
            ->where('YEAR(i.dateInvoice) = :year') // Filtrage par année
            ->setParameter('year', $year) // Sécurisation du paramètre
            ->orderBy('i.dateInvoice', 'ASC'); // Ordre chronologique sur la date de la facture

        //Renvoie du résultat
        // getQuery() retourne l'objet Query Doctrine qui permet d'exécuter la requête construite
        // getResult() exécute la requête et retourne les résultats sous forme d'un tableau d'entités
        return $queryBuilder->getQuery()->getResult(); 
    }

}
