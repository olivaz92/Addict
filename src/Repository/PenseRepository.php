<?php

namespace App\Repository;

use App\Entity\Pense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Pense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pense[]    findAll()
 * @method Pense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pense::class);
    }

    // /**
    //  * @return Pense[] Returns an array of Pense objects
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
    public function findOneBySomeField($value): ?Pense
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
