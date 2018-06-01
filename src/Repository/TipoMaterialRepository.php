<?php

namespace App\Repository;

use App\Entity\TipoMaterial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TipoMaterial|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoMaterial|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoMaterial[]    findAll()
 * @method TipoMaterial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoMaterialRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TipoMaterial::class);
    }

    public function findLikeNome($nome)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('upper(a.nome) LIKE upper(:nome)')
            ->setParameter('nome', "%$nome%")
            ->orderBy('a.nome')
            ->setMaxResults(10)
            ->getQuery()
            ->execute()
        ;
    }

    /*
    public function findOneBySomeField($value): ?TipoMaterial
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
