<?php

namespace App\Repository;

use App\Entity\CategoryL;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryL>
 *
 * @method CategoryL|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryL|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryL[]    findAll()
 * @method CategoryL[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryLRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryL::class);
    }

//    /**
//     * @return CategoryL[] Returns an array of CategoryL objects
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

//    public function findOneBySomeField($value): ?CategoryL
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
