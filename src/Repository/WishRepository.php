<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wish[]    findAll()
 * @method Wish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

//    public function selectAllWishesIdForUser($userId)
//    {
//        return $this->createQueryBuilder('w')
//            ->select('w.id')
//            ->where('w.user = :userId')
//            ->setParameter('userId', $userId)
//            ->getQuery()->getResult();
//    }
//
//    public function findCurrentWishId($wish)
//    {
//        return $this->createQueryBuilder('w')
//            ->select('w.user')
//            ->where('w.id = :wish')
//            ->setParameter('wish', $wish)
//            ->getQuery()->getResult();
//    }
//
//    public function test()
//    {
//        return $this->createNamedQuery()
//    }
    // /**
    //  * @return Wish[] Returns an array of Wish objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Wish
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
