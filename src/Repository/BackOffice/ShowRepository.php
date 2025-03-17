<?php

namespace App\Repository\BackOffice;

use App\Entity\Show;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Show>
 */
class ShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Show::class);
    }

    public function findUsedRoomInShow(int $exhibitionId, ?int $showId): array
    {
        // Crée un QueryBuilder pour construire une requête DQL.
        $qb = $this->createQueryBuilder('s')
            // Sélectionne uniquement les IDs des salles (room.id).
            ->select('r.id')
            // Joint la table 'room' à la table 'show' via la relation 'room'.
            ->join('s.room', 'r')
            // Filtre les shows pour ne récupérer que ceux appartenant à l'exposition spécifiée.
            ->where('s.exhibition = :exhibitionId')
            // Définit la valeur du paramètre :exhibitionId.
            ->setParameter('exhibitionId', $exhibitionId);

        // Si un showId est fourni (lors de la modification d'un show),
        if ($showId !== null) {
            // Exclut le show actuel des résultats.
            $qb->andWhere('s.id != :showId')
                // Définit la valeur du paramètre :showId.
                ->setParameter('showId', $showId);
        }

        // Exécute la requête et retourne un tableau contenant uniquement les IDs des salles.
        return $qb->getQuery()->getSingleColumnResult();
    }
}
