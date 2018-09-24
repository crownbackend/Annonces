<?php

namespace App\Repository;

use App\Entity\ConversationUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ConversationUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConversationUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConversationUsers[]    findAll()
 * @method ConversationUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationUsersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ConversationUsers::class);
    }

//    /**
//     * @return ConversationUsers[] Returns an array of ConversationUsers objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConversationUsers
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
