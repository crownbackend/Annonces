<?php

namespace App\Repository;

use App\Entity\Messages;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Messages::class);
    }


    /**
     * @return Messages|null
     * @param User $from
     * @param User $to
     * @throws \Exception
     */
    public function findByMyMessages(User $from, User $to) {
//SELECT * FROM `messages` WHERE advertisement_id = 208 ORDER BY created_at
        $query = $this->createQueryBuilder('m')
        ->addSelect('m')
        ->where('m.fromId = :from')
        ->andWhere('m.toId = :to')
        ->setParameter(':from', $from)
        ->setParameter(':to', $to)
        ->getQuery()
        ;

        try {
            return $query->getResult();
        }
        catch (\Exception $e)
        {
            throw new \Exception('Prolbème : '. $e->getMessage(). $e->getLine(). $e->getFile());
        }
    }

}
