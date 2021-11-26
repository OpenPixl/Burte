<?php

namespace App\Repository\Gestapp;

use App\Entity\Gestapp\EventGal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventGal|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventGal|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventGal[]    findAll()
 * @method EventGal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventGalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventGal::class);
    }

    public function EventGalPublish($idevent)
    {
        return $this->createQueryBuilder('e')
            ->join('e.event', 'ev')
            ->andWhere('ev.id = :idevent')
            ->setParameter('idevent', $idevent)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return EventGal[] Returns an array of EventGal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventGal
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
