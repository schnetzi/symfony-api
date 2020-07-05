<?php

namespace App\Repository;

use App\Entity\TipTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipTicket[]    findAll()
 * @method TipTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipTicket::class);
    }

    // /**
    //  * @return TipTicket[] Returns an array of TipTicket objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipTicket
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
