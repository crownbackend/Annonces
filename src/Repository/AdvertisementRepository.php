<?php

namespace App\Repository;

use App\Entity\Advertisement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Advertisement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advertisement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advertisement[]    findAll()
 * @method Advertisement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertisementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Advertisement::class);
    }



    /**
     * @param int $id
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findByRegions(string $id) {
//SELECT * FROM `advertisement` WHERE region_id = 1
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->from('App\Entity\Advertisement', 'r')
            ->where('a.region = :id')
            ->setParameter(':id', $id)
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
