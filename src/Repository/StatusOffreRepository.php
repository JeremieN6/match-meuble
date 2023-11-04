<?php

namespace App\Repository;

use App\Entity\StatusOffre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StatusOffre>
 *
 * @method StatusOffre|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatusOffre|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatusOffre[]    findAll()
 * @method StatusOffre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusOffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatusOffre::class);
    }

//    /**
//     * @return StatusOffre[] Returns an array of StatusOffre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StatusOffre
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
