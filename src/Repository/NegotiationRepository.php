<?php

namespace App\Repository;

use App\Entity\Negotiation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Negotiation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Negotiation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Negotiation[]    findAll()
 * @method Negotiation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NegotiationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Negotiation::class);
    }

    /**
     * @param $user_id
     * @param $product_id
     * @return Negotiation|null
     */
    public function findOneByMaxDate($user_id, $product_id)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user_id = :user_id')
            ->andWhere('p.product_id = :product_id')
            ->setParameter('user_id', $user_id)
            ->setParameter('product_id', $product_id)
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();

    }

    /**
     * @param $user_id
     * @param $product_id
     * @param $transaction_id
     * @return Integer
     */
    public function findNegotiationCount($user_id, $product_id, $transaction_id)
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.user_id = :user_id')
            ->andWhere('p.product_id = :product_id')
            ->andwhere('p.transactionId = :transaction_id')
            ->setParameter('user_id', $user_id)
            ->setParameter('product_id', $product_id)
            ->setParameter('transaction_id', $transaction_id)
            ->getQuery()
            ->getSingleScalarResult();

    }


    /**
     * @param $user_id
     * @param $product_id
     * @param $negotiationCategory
     * @return Negotiation[]
     */
    public function findByUserProductAndCategory($user_id, $product_id, $negotiationCategory)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user_id = :user_id')
            ->andWhere('p.product_id = :product_id')
            ->andWhere('p.negotiationCategory = :negotiationCategory')
            ->andWhere('p.transactionId = 0')
            ->andWhere('p.acceptedOffer = true')
            ->setParameter('user_id', $user_id)
            ->setParameter('product_id', $product_id)
            ->setParameter('negotiationCategory', $negotiationCategory)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $user_id
     * @param $product_id
     * @param $transaction_id
     * @return Negotiation[]
     */
    public function findByUserProductAndTransaction($user_id, $product_id, $transaction_id)
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.user_id = :user_id')
            ->andWhere('p.product_id = :product_id')
            ->andwhere('p.transactionId = :transaction_id')
            ->setParameter('user_id', $user_id)
            ->setParameter('product_id', $product_id)
            ->setParameter('transaction_id', $transaction_id)
            ->getQuery()
            ->getResult()
            ;
    }





    // /**
    //  * @return Negotiation[] Returns an array of Negotiation objects
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
    public function findOneBySomeField($value): ?Negotiation
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
