<?php

namespace App\Repository;

use App\Entity\TypeDeMeuble;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeDeMeuble>
 *
 * @method TypeDeMeuble|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeDeMeuble|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeDeMeuble[]    findAll()
 * @method TypeDeMeuble[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeDeMeubleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDeMeuble::class);
    }

//    /**
//     * @return TypeDeMeuble[] Returns an array of TypeDeMeuble objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeDeMeuble
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
