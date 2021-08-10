<?php

namespace App\Repository\GestApp;

use App\Entity\GestApp\Recommandation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recommandation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recommandation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recommandation[]    findAll()
 * @method Recommandation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecommandationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recommandation::class);
    }

    /**
     * @param $user
     * @return int|mixed|string
     * Liste les recommandation reçues
     */
    public function findByUserReceipt($user)
    {
        return $this->createQueryBuilder('r')
            ->join('r.member', 'm')
            ->andWhere('m.id = :id')
            ->setParameter('id', $user)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $user
     * @return int|mixed|string
     * Liste les recommandation envoyées
     */
    public function findByUserSend($user)
    {
        return $this->createQueryBuilder('r')
            ->join('r.author', 'm')
            ->andWhere('m.id = :id')
            ->setParameter('id', $user)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function statsByUser($user)
    {
        return $this->createQueryBuilder('r')
            ->addSelect('
                SUM(r.recoPrice) AS recoPrice,
                r.recoState,
                m.id
            ')
            ->join('r.member', 'm')
            ->andWhere('m.id = :id')
            ->setParameter('id', $user)
            ->addGroupBy('r.recoState')
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Recommandation[] Returns an array of Recommandation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recommandation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
