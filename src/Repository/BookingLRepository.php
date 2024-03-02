<?php

namespace App\Repository;

use App\Entity\BookingL;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookingL>
 *
 * @method BookingL|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingL|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingL[]    findAll()
 * @method BookingL[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingLRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookingL::class);
    }

//    /**
//     * @return BookingL[] Returns an array of BookingL objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BookingL
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
