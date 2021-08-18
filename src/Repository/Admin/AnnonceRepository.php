<?php

namespace App\Repository\Admin;

use App\Entity\Admin\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, annonce::class);
    }

    /**
    * @return annonce[] Returns an array of annonce objects
    */
    public function publishDashboard($current)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager
            ->createQuery(
                "
                SELECT 
                    a.id,
                    a.title As title,
                    a.content AS content,
                    a.publishAt AS publishAt,
                    a.dispublishAt AS dispublishAt,
                    s.logoStructureName AS logoStructureName
                FROM App\Entity\Admin\Annonce a
                JOIN a.author m
                JOIN m.structure s
                WHERE (:current BETWEEN a.publishAt AND a.dispublishAt)
                "
            )
            ->setParameter('current', $current);

        // Retourne un tabelau associatif
        return $query->getResult();

    }


    // /**
    //  * @return annonce[] Returns an array of annonce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?annonce
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
