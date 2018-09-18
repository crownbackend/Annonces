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
        $query = $this->createQueryBuilder('a')
            ->select('count(a)')
            ->getQuery();
        try {
            return $query->getSingleScalarResult();
        }
        catch (\Exception $e) {
            throw new \Exception('Problème' . $e->getMessage() . $e->getLine());
        }
    }

    /**
     * @param string $regionSlug
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findByRegions(string $regionSlug) {
//SELECT * FROM `advertisement` WHERE region_id = 1
        $isValid = 1;
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->from('App\Entity\Advertisement', 'r')
            ->join('a.region', 'i')
            ->where('i.regionSlug = :regionSlug')
            ->andWhere('a.isValid = :isValid')
            ->setParameter(':regionSlug', $regionSlug)
            ->setParameter(':isValid', $isValid)
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
     * @param string $advertisementSlug
     * @param string $categorySlug
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findBySlugAdvertisement($advertisementSlug, $categorySlug){
//SELECT * FROM `advertisement` WHERE slug = 'machine-a-laver'
        $isValid = 1;
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->from('App\Entity\Advertisement', 'r')
            ->join('a.category', 'c')
            ->where('a.advertisementSlug = :advertisementSlug')
            ->andWhere('c.categorySlug = :categorySlug')
            ->andWhere('a.isValid = :isValid')
            ->setParameter(':advertisementSlug', $advertisementSlug)
            ->setParameter(':categorySlug', $categorySlug)
            ->setParameter(':isValid', $isValid)
            ->getQuery();

        try {
            return $query->getOneOrNullResult();
        }
        catch(\Exception $e) {
            throw new \Exception('problème '. $e->getMessage(). $e->getFile(). $e->getFile());
        }
    }

    /**
     * @param $user
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findByMyAdvertisement($user)
    {   //SELECT * FROM `advertisement` WHERE user_id = $user
        $query = $this->createQueryBuilder('a')
        ->select('a')
        ->from('App\Entity\Advertisement', 'r')
        ->where('a.user = :user')
        ->setParameter(':user', $user)
        ->getQuery()
        ;

        try {
            return $query->getResult();
        }
        catch(\Exception $e) {
            throw new \Exception('problème '. $e->getMessage(). $e->getFile(). $e->getFile());
        }
    }

    /**
     * @param $user
     * @return integer|null
     * @throws \Exception
     */
    public function findByCountMyAdvertisement($user)
    {
        $query = $this->createQueryBuilder('a')
            ->select('count(a)')
            ->where('a.user = :user')
            ->setParameter(':user', $user)
            ->getQuery();
        try {
            return $query->getSingleScalarResult();
        }
        catch (\Exception $e) {
            throw new \Exception('Problème' . $e->getMessage() . $e->getLine());
        }
    }

}
