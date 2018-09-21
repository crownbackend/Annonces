<?php

namespace App\Repository;

use App\Entity\Advertisement;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @param User $user
     * @param Advertisement $advertisement
     * @return Message|null
     * @throws \Exception
     */
    public function findByMessages(User $user, Advertisement $advertisement) {

    // SELECT * FROM `message` WHERE advertisement_id = 5 AND user_id = 1
        $query = $this->createQueryBuilder('m')
        ->select('m')
        ->from('App\Entity\Message', 'n')
        ->join('m.advertisement', 'a')
        ->where('m.advertisement = :advertisement')
        ->andWhere('m.user = :user')
        ->setParameter(':user', $user)
        ->setParameter(':advertisement', $advertisement)
        ->getQuery()
        ;

        try {
            return $query->getResult();
        }
        catch (\Exception $e) {
            throw new \Exception('Problème' . $e->getMessage() . $e->getLine());
        }
    }

}
