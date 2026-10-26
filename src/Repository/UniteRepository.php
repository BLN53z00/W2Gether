<?php

namespace App\Repository;

use App\Entity\Unite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Unite>
 */
class UniteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unite::class);
    }

    public function findByClient(\App\Entity\Client $client): array
    {
        return $this->createQueryBuilder('u')
            ->join('u.commande', 'c')
            ->join('u.baie', 'b')
            ->addSelect('b')
            ->where('c.client = :client')
            ->setParameter('client', $client)
            ->orderBy('b.numero', 'ASC')
            ->addOrderBy('u.numero', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Unite[] Returns an array of Unite objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Unite
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
