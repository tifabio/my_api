<?php

namespace App\Repository;

use App\Entity\JobUpdate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method JobUpdate|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobUpdate|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobUpdate[]    findAll()
 * @method JobUpdate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobUpdateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobUpdate::class);
    }

    // /**
    //  * @return JobUpdate[] Returns an array of JobUpdate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JobUpdate
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
