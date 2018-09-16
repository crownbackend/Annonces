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
     * @return integer|null
     * @throws \Exception
     */
    public function findByCount()
    {
        $query = $this->createQueryBuilder('n')
            ->select('count(n)')
            ->getQuery();
        try {
            return $query->getSingleScalarResult();
        }
        catch (\Exception $e) {
            throw new \Exception('Problème' . $e->getMessage() . $e->getLine());
        }
    }

    /**
     * @param int $id
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findByRegions(int $id) {
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

    /**
     * @param string $slug
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findBySlug($slug){
//SELECT * FROM `advertisement` WHERE slug = 'machine-a-laver'
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->from('App\Entity\Advertisement', 'r')
            ->where('a.slug = :slug')
            ->setParameter(':slug', $slug)
            ->getQuery();

        try {
            return $query->getOneOrNullResult();
        }
        catch(\Exception $e) {
            throw new \Exception('problème '. $e->getMessage(). $e->getFile(). $e->getFile());
        }
    }

}
