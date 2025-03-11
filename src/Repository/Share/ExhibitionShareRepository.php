<?php

namespace App\Repository\Share;

use App\Entity\Exhibition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exhibition>
 */
class ExhibitionShareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exhibition::class);
    }

    /******************** Sélectionne les 3 dernières expositions prévues ********************/
    public function findNextExhibition() {
        // Récupération de l'EntityManager pour interagir avec la base de données
        $entityManager = $this->getEntityManager();
        // Création du QueryBuilder (spécifique Symfony) pour construire la requête DQL
        $queryBuilder = $entityManager->createQueryBuilder();
       

        $queryBuilder->select('e')
            ->from('App\Entity\Exhibition', 'e')
            ->where('e.dateExhibit > :now') //now() ne fonctionne pas seul, il faut setParameter
            ->setParameter('now', new \DateTime()) 
            ->orderBy('e.dateExhibit', 'DESC')
            ->setMaxResults(3);  //Limit en sql

        //Renvoie du résultat
        // getQuery() retourne l'objet Query Doctrine qui permet d'exécuter la requête construite
        // getResult() exécute la requête et retourne les résultats sous forme d'un tableau d'entités
        return $queryBuilder->getQuery()->getResult(); 
    }
    


    
    /******************** Sélectionne toutes les dernières expositions prévues ********************/
    public function findAllNextExhibition() {
        // Récupération de l'EntityManager pour interagir avec la base de données
        $entityManager = $this->getEntityManager();
        // Création du QueryBuilder (spécifique Symfony) pour construire la requête DQL
        $queryBuilder = $entityManager->createQueryBuilder();
       

        $queryBuilder->select('e')
            ->from('App\Entity\Exhibition', 'e')
            ->where('e.dateExhibit > :now') //now() ne fonctionne pas seul, il faut setParameter
            ->setParameter('now', new \DateTime()) 
            ->orderBy('e.dateExhibit', 'ASC');

        //Renvoie du résultat
        // getQuery() retourne l'objet Query Doctrine qui permet d'exécuter la requête construite
        // getResult() exécute la requête et retourne les résultats sous forme d'un tableau d'entités
        return $queryBuilder->getQuery()->getResult(); 
    }

    public function findAllInfosExhibition() {
         // Récupération de l'EntityManager pour interagir avec la base de données
         $entityManager = $this->getEntityManager();
         // Création du QueryBuilder (spécifique Symfony) pour construire la requête DQL
         $queryBuilder = $entityManager->createQueryBuilder();

         $queryBuilder->select('s', 'e', 'a', 'r')
         ->from('App\Entity\Show', 's')
         ->innerJoin('s.exhibition', 'e') 
         ->innerJoin('s.artist', 'a')     
         ->innerJoin('s.room', 'r');       

        //Renvoie du résultat
        // getQuery() retourne l'objet Query Doctrine qui permet d'exécuter la requête construite
        // getResult() exécute la requête et retourne les résultats sous forme d'un tableau d'entités
        return $queryBuilder->getQuery()->getResult(); 
    }

/************ Artistes non prévus sur un show *****************************/
    public function findUnplannedArtists($exhibition_id)
    {
        // Récupération de l'EntityManager pour interagir avec la base de données
        $entityManager = $this->getEntityManager();
        // Création du QueryBuilder (spécifique Symfony) pour construire la requête DQL
        $queryBuilder = $entityManager->createQueryBuilder();

        //Sélectionne artist d'une session dont l'id est passé en paramètre
        $queryBuilder->select('a.id')
            ->from('App\Entity\Artist', 'a')
            ->leftJoin('a.shows', 's')
            ->where('s.exhibition = :id');

        $sub = $entityManager->createQueryBuilder();
        //Sélectionne artistes qui ne sont pas (NOT IN) dans le résultat précédent
        //on obtient artistes non inscrits pour une expo
        $sub->select('art')
            ->from('App\Entity\Artist', 'art')
            ->where($sub->expr()->notIn('art.id', $queryBuilder->getDQL()))
            //requete paramétrée
            ->setParameter('id', $exhibition_id)
            //trier liste des artistes sur le nom de famille
            ->orderBy('art.artistName', 'ASC');

        //Renvoie du résultat
        $query = $sub->getQuery();
        return $query->getResult();
    }
}
