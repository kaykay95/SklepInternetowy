<?php

namespace App\Repository;

use App\Entity\NegotiationExpression;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NegotiationExpression|null find($id, $lockMode = null, $lockVersion = null)
 * @method NegotiationExpression|null findOneBy(array $criteria, array $orderBy = null)
 * @method NegotiationExpression[]    findAll()
 * @method NegotiationExpression[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NegotiationExpressionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NegotiationExpression::class);
    }

    // /**
    //  * @return NegotiationExpression[] Returns an array of NegotiationExpression objects
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
    public function findOneBySomeField($value): ?NegotiationExpression
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
