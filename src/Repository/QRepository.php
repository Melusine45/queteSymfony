<?php

namespace App\Repository;

use App\Entity\Q;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Q|null find($id, $lockMode = null, $lockVersion = null)
 * @method Q|null findOneBy(array $criteria, array $orderBy = null)
 * @method Q[]    findAll()
 * @method Q[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Q::class);
    }

    // /**
    //  * @return Q[] Returns an array of Q objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Q
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
