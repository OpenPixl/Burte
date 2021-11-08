<?php

namespace App\Repository\GestApp;

use App\Entity\GestApp\ProductUnit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductUnit|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductUnit|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductUnit[]    findAll()
 * @method ProductUnit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductUnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductUnit::class);
    }

    // /**
    //  * @return ProductCategory[] Returns an array of ProductCategory objects
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
    public function findOneBySomeField($value): ?ProductCategory
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
