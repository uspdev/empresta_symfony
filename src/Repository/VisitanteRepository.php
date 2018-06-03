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

    public function findLike($nome)
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

    public function findAll()
    {
        return $this->findBy(array(), array('nome' => 'ASC'));
    }

    public function add(Visitante $visitante, $flush = true)
    {
        $this->getEntityManager()->persist($visitante);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
