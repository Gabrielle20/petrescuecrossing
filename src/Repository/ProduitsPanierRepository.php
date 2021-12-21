<?php

namespace App\Repository;

use App\Entity\ProduitsPanier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProduitsPanier|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitsPanier|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitsPanier[]    findAll()
 * @method ProduitsPanier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsPanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitsPanier::class);
    }

    // /**
    //  * @return ProduitsPanier[] Returns an array of ProduitsPanier objects
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
    public function findOneBySomeField($value): ?ProduitsPanier
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
