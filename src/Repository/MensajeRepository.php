<?php

namespace App\Repository;

use App\Entity\Mensaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class MensajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mensaje::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countUnreadMessagesByUser($userId)
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.receptor = :userId')
            ->andWhere('m.leido = :leido')
            ->setParameter('userId', $userId)
            ->setParameter('leido', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function markMessagesAsRead($userId)
    {
        $qb = $this->createQueryBuilder('m');
        $q = $qb->update()
            ->set('m.leido', $qb->expr()->literal(true))
            ->where('m.receptor = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();
        $q->execute();
    }

}

