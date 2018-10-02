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
     * @param Advertisement $advertisement
     * @throws \Exception
     */
    public function findByMyMessages(Advertisement $advertisement) {
//SELECT * FROM `messages` WHERE advertisement_id = 208 ORDER BY created_at
        $query = $this->createQueryBuilder('m')
        ->addSelect('m')
        ->where('m.advertisement = :advertisement')
        ->setParameter(':advertisement', $advertisement)
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
