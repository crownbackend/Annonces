<?php

namespace App\Repository;

use App\Entity\Advertisement;
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
     * @param User $from2
     * @param User $to
     * @param User $to2
     * @param Advertisement $advertisement
     * @throws \Exception
     */
    public function findByMyMessages(User $from, User $to, Advertisement $advertisement, User $from2, User $to2) {

        $query = $this->createQueryBuilder('m')
        ->addSelect('m')
        ->where('m.fromId = :from')
        ->andWhere('m.toId = :to')
        ->andWhere('m.advertisement = :advertisement')
        ->orWhere('m.fromId = :from2')
        ->andWhere('m.toId = :to2')
        ->andWhere('m.advertisement = :advertisement')
        ->setParameter(':from', $from)
        ->setParameter(':to', $to)
        ->setParameter(':from2', $from2)
        ->setParameter(':to2', $to2)
        ->setParameter(':advertisement', $advertisement)
        ->orderBy('m.createdAt', 'DESC')
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
