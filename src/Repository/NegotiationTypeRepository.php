<?php

namespace App\Repository;

use App\Entity\NegotiationCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NegotiationCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method NegotiationCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method NegotiationCategory[]    findAll()
 * @method NegotiationCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NegotiationTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NegotiationCategory::class);
    }

    // /**
    //  * @return NegotiationCategory[] Returns an array of NegotiationCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NegotiationCategory
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
