<?php

namespace App\Repository\Gestapp;

use App\Entity\Gestapp\Recommandation;
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
     * Liste les recommandation reçues lues
     */
    public function findByUserReceiptRead($user)
    {
        return $this->createQueryBuilder('r')
            ->join('r.member', 'm')
            ->andWhere('m.id = :id')
            ->andWhere('r.isRead = 0')
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

    /**
     * @param $user
     * @return int|mixed|string
     * Liste les recommandation envoyées lues
     */
    public function findByUserSendRead($user)
    {
        return $this->createQueryBuilder('r')
            ->join('r.author', 'm')
            ->andWhere('m.id = :id')
            ->andWhere('r.isRead = 0')
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
     * Liste les recommandations envoyées et reçues
     */
    public function statsByUser($user)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager
            ->createQuery(
            "
                SELECT 
                    r.recoState AS recoState, 
                    SUM(CASE WHEN m.id = :user THEN r.recoPrice ELSE 0 END) AS recoPriceReceipt,
                    SUM(CASE WHEN a.id = :user THEN r.recoPrice ELSE 0 END) AS recoPriceSend
                FROM App\Entity\Gestapp\Recommandation r
                JOIN r.author a
                JOIN r.member m
                GROUP BY r.recoState
                ORDER BY r.recoState ASC
                "
            )
        ->setParameter('user', $user);

        // Retourne un tabelau associatif
        return $query->getResult();
    }

    /**
     * @param $user
     * @return int|mixed|string
     * Liste les recommandations envoyées et reçues
     */
    public function countup()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager
            ->createQuery(
                "
                SELECT 
                    r.recoState AS recoState, 
                    SUM(CASE WHEN m.id = :user THEN r.recoPrice ELSE 0 END) AS recoPriceReceipt,
                    SUM(CASE WHEN a.id = :user THEN r.recoPrice ELSE 0 END) AS recoPriceSend
                FROM App\Entity\Gestapp\Recommandation r
                JOIN r.author a
                JOIN r.member m
                GROUP BY r.recoState
                ORDER BY r.recoState ASC
                "
            )
            ->setParameter('user', $user);

        // Retourne un tabelau associatif
        return $query->getResult();
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
