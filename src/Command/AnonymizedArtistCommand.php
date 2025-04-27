<?php

namespace App\Command;

use App\Entity\Artist;
use App\Repository\BackOffice\ArtistBORepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface; //Gestion des entrées de la commande
use Symfony\Component\Console\Output\OutputInterface; //Gestion des sorties de la commande

class AnonymizeArtistsCommand extends Command
{
    protected static $defaultName = 'app:anonymizeArtists'; // Nom de la commande qui sera utilisé dans le terminal
    private EntityManagerInterface $entityManager;
    private ArtistBORepository $artistBORepo;

    public function __construct(
        EntityManagerInterface $entityManager,
        ArtistBORepository $artistBORepo)
    {
        $this->entityManager = $entityManager;
        $this->artistBORepo = $artistBORepo;
        parent::__construct(); //Appelle Command::__construct() pour init la cmd
    }

    //Permet de configurer la commande
    protected function configure(): void
    {
        $this
            ->setDescription('Anonymise les artistes dont la date d\'anonymisation (anonymizeAt) est atteinte.');
    }

    // Permet d'exécuter la commande
    protected function execute(
        InputInterface $input, 
        OutputInterface $output): int
    {
        $now = new \DateTimeImmutable();
        $artistsToAnonymize = $this->artistBORepo
            ->createQueryBuilder('a')
            ->where('a.anonymizeAt IS NOT NULL')
            ->andWhere('a.anonymizeAt <= :now') // Date d'anonymisation inférieure ou égale à today
            ->andWhere('a.isAnonymized = :isAnonymized') //isAnonymized = 0
            ->setParameter('now', $now)
            ->setParameter('isAnonymized', false)
            ->getQuery()
            ->getResult();

        $count = 0; //Pour le feed back
        foreach ($artistsToAnonymize as $artist) {
            $artist->setArtistBirthDate(null);
            $artist->setArtistDeathDate(null);
            $artist->setArtistBio(null);
            $artist->setIsAnonymized(true);
            $artist->setAnonymizeAt(null); // Réinitialiser la date d'anonymisation

            $this->entityManager->persist($artist);
            $count++;
        }

        $this->entityManager->flush();

        $output->writeln(sprintf('%d artiste(s) ont été anonymisé(s).', $count));

        return Command::SUCCESS;
    }
}