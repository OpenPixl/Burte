<?php

namespace App\Repository\Gestapp;

use App\Entity\Gestapp\ProductCustomize;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductCustomize|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCustomize|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCustomize[]    findAll()
 * @method ProductCustomize[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCustomizeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCustomize::class);
    }

    // /**
    //  * @return ProductCustomize[] Returns an array of ProductCustomize objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductCustomize
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
