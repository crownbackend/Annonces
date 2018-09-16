<?php

namespace App\Repository;

use App\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Region|null find($id, $lockMode = null, $lockVersion = null)
 * @method Region|null findOneBy(array $criteria, array $orderBy = null)
 * @method Region[]    findAll()
 * @method Region[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Region::class);
    }

    /**
     * @param string $regionSlug
     * @return Region|null
     * @throws \Exception
     */
    public function findBySlugRegion(string $regionSlug) {
//SELECT * FROM `region` WHERE slug
        $query = $this->createQueryBuilder('r')
            ->select('r')
            ->from('App\Entity\Region', 'i')
            ->where('r.regionSlug = :regionSlug')
            ->setParameter(':regionSlug', $regionSlug)
            ->getQuery()
        ;
        try {
            return $query->getResult();
        }
        catch (\Exception $e) {
            throw new \Exception('Problème' . $e->getMessage() . var_dump($e));
        }

    }

}
