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
     * @throws \Exception
     */
    public function findByMessages() {

        $query = $this->createQueryBuilder('m')
        ->select('m')
        ->getQuery()
        ;


        try {
            return $query->getResult();
        }
        catch(\Exception $e) {
            throw new \Exception('problème '. $e->getMessage(). $e->getFile(). $e->getFile());
        }
    }

}
