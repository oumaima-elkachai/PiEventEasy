<?php

namespace App\Repository;

use App\Entity\CategoryE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryE>
 *
 * @method CategoryE|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryE|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryE[]    findAll()
 * @method CategoryE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryE::class);
    }

//    /**
//     * @return CategoryE[] Returns an array of CategoryE objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategoryE
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
