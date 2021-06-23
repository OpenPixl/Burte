<?php

namespace App\Repository\Webapp;

use App\Entity\Webapp\MediaOne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediaOne|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaOne|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaOne[]    findAll()
 * @method MediaOne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaOneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaOne::class);
    }

    // /**
    //  * @return MediaOne[] Returns an array of MediaOne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findBySection($idsection): ?MediaOne
    {
        return $this->createQueryBuilder('m')
            ->andWhere('s.id = :idsection')
            ->innerJoin('s.UniqImage')
            ->setParameter('idsection', $idsection)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
