<?php

namespace App\Repository;

use App\Entity\ClientDesk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientDesk|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientDesk|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientDesk[]    findAll()
 * @method ClientDesk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientDeskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientDesk::class);
    }

    // /**
    //  * @return ClientDesk[] Returns an array of ClientDesk objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClientDesk
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
