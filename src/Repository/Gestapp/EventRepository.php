<?php

namespace App\Repository\Gestapp;

use App\Entity\Gestapp\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function ListAllEventPublish($current)
    {
        return $this->createQueryBuilder('e')
            ->addSelect('
                e.id, 
                e.name, 
                e.description,
                e.visuelName,
                e.isPublish,
                e.eventAt,
                e.eventtimeAt,
                e.placeAddress,
                e.placeComplement,
                e.placeZipcode,
                e.placeCity,
                e.isValidBy
                 ')
            ->andWhere('e.isPublish = :isPublish AND e.isValidBy = :isValidBy AND e.eventAt > :current')
            ->setParameter('isPublish', 1)
            ->setParameter('isValidBy', 1)
            ->setParameter( 'current', $current)
            ->orderBy('e.eventAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function ListEventPublishMember($idmember)
    {
        return $this->createQueryBuilder('e')
            ->join('e.author', 'm')
            ->andWhere('m.id = :idmember')
            ->setParameter('idmember', $idmember)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
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
    public function findOneBySomeField($value): ?Event
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
