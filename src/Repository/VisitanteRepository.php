<?php

namespace App\Repository;

use App\Entity\Visitante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Visitante|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visitante|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visitante[]    findAll()
 * @method Visitante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitanteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Visitante::class);
    }

//    /**
//     * @return Visitante[] Returns an array of Visitante objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Visitante
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
