<?php

namespace App\Repository\Gestapp;

use App\Entity\Gestapp\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
    * Liste les produite selon une catégorie.
    * @return Product[] Returns an array of Product objects
    */
    public function oneCategory($idcat)
    {
        return $this->createQueryBuilder('p')

            ->leftJoin('p.producer', 'pr')
            ->leftJoin('pr.structure', 's')
            ->leftJoin('p.category', 'c')
            ->Select('
                p.id AS id,
                p.name AS name, 
                p.description AS description,
                p.details,
                p.price,
                p.quantity,
                p.productName AS productName,
                c.id,
                c.name,
                p.isDisponible,
                p.isStar,
                p.isOnLine,
                s.name AS producer,
                s.logoStructureName AS logoStructureName
                 ')
            ->andWhere('c.id = :idcat')
            ->andWhere('p.isOnLine = :isOnLine')
            ->setParameter('idcat', $idcat)
            ->setParameter('isOnLine', 1)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Liste tous les produits en ligne.
     * @return Product[] Returns an array of Product objects
     */
    public function listALlProduct()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.producer', 'pr')
            ->leftJoin('pr.structure', 's')
            ->leftJoin('p.category', 'c')
            ->Select('
                p.id AS id,
                p.name AS name, 
                p.description AS description,
                p.details,
                p.price,
                p.quantity,
                p.productName AS productName,
                c.id AS categoryId,
                c.name,
                p.isDisponible,
                p.isStar,
                p.isOnLine,
                s.name AS producer,
                s.logoStructureName AS logoStructureName
                 ')
            ->andWhere('p.isOnLine = :isOnLine')
            ->setParameter('isOnLine', 1)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
