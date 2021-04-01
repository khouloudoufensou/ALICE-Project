<?php

namespace App\Repository;

use App\Entity\StageFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StageFormation|null find($id, $lockMode = null, $lockVersion = null)
 * @method StageFormation|null findOneBy(array $criteria, array $orderBy = null)
 * @method StageFormation[]    findAll()
 * @method StageFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StageFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StageFormation::class);
    }

    // /**
    //  * @return StageFormation[] Returns an array of StageFormation objects
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
    public function findOneBySomeField($value): ?StageFormation
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
