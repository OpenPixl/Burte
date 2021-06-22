<?php

namespace App\Repository\Webapp;

use App\Entity\Webapp\Section;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Section::class);
    }

    public function findbypage($page){
        return $this->createQueryBuilder('s')
            ->select('s.id, s.title, s.slug, s.description,s.favorites, s.contentType, s.attrId, s.attrName, s.attrClass, s.createdAt, s.updatedAt, p.id, a.id As oneArticle')
            ->join('s.page', 'p')
            ->join('s.oneArticle', 'a')
            ->Where('p.id = :page')
            ->setParameter('page', $page)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Section[] Returns an array of Section objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Section
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
