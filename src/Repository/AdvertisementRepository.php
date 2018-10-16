<?php

namespace App\Repository;

use App\Entity\Advertisement;
use App\Entity\User;
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
     * @param int $isValid
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findByRegions(string $regionSlug, int $isValid)
    {
//SELECT * FROM `advertisement` WHERE region_id = 1

        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->from('App\Entity\Advertisement', 'r')
            ->join('a.region', 'i')
            ->where('i.regionSlug = :regionSlug')
            ->andWhere('a.isValid = :isValid')
            ->orderBy("a.createdAt", "desc")
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
     * @param int $isValid
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findBySlugAdvertisement($advertisementSlug, $categorySlug, $isValid)
    {
//SELECT * FROM `advertisement` WHERE slug = 'machine-a-laver'
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
     * @param $isValid
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findByMyAdvertisementValid($user, $isValid)
    {
        //SELECT * FROM `advertisement` WHERE user_id = $user is valid = 1
        $query = $this->createQueryBuilder('a')
        ->select('a')
        ->from('App\Entity\Advertisement', 'r')
        ->where('a.user = :user')
        ->andWhere('a.isValid = :isValid')
        ->orderBy('a.createdAt', 'DESC')
        ->setParameter(':user', $user)
        ->setParameter(':isValid', $isValid)
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
     * @param $notValid
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findByMyAdvertisementNotValid($user, $notValid)
    {
        //SELECT * FROM `advertisement` WHERE user_id = $user is valid = 1
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->from('App\Entity\Advertisement', 'r')
            ->where('a.user = :user')
            ->andWhere('a.isValid = :isValid')
            ->setParameter(':user', $user)
            ->setParameter(':isValid', $notValid)
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
     * @param User $user
     * @param int $isValid
     * @return integer|null
     * @throws \Exception
     */
    public function findByCountMyAdvertisementValid(User $user, int $isValid)
    {
        $query = $this->createQueryBuilder('a')
            ->select('count(a)')
            ->where('a.user = :user')
            ->andWhere('a.isValid = :isValid')
            ->setParameter(':user', $user)
            ->setParameter(':isValid', $isValid)
            ->getQuery();
        try {
            return $query->getSingleScalarResult();
        }
        catch (\Exception $e) {
            throw new \Exception('Problème' . $e->getMessage() . $e->getLine());
        }
    }

    /**
     * @param User $user
     * @param int $isValid
     * @return integer|null
     * @throws \Exception
     */
    public function findByCountMyAdvertisementNotValid(User $user, int $isValid)
    {
        $query = $this->createQueryBuilder('a')
            ->select('count(a)')
            ->where('a.user = :user')
            ->andWhere('a.isValid = :isValid')
            ->setParameter(':user', $user)
            ->setParameter(':isValid', $isValid)
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
    public function findByMessages(int $id): ?Advertisement
    {

        $query = $this->createQueryBuilder('a')
        ->select('a')
        ->from('App:Advertisement', 'r')
        ->where('a.id = :id')
        ->leftJoin('a.message', 'm')
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
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findByCountValid()
    {

        $query = $this->createQueryBuilder('a')
        ->select('count(a)')
        ->where('a.isValid = :isValid')
        ->setParameter(':isValid', 1)
        ->getQuery();

        try {
            return $query->getSingleScalarResult();
        }
        catch (\Exception $e) {
            throw new \Exception('Problème : ' . $e->getMessage() . $e->getLine());
        }

    }

    /**
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findByCountNotValid()
    {

        $query = $this->createQueryBuilder('a')
            ->select('count(a)')
            ->where('a.isValid = :isValid')
            ->setParameter(':isValid', 0)
            ->getQuery();

        try {
            return $query->getSingleScalarResult();
        }
        catch (\Exception $e) {
            throw new \Exception('Problème : ' . $e->getMessage() . $e->getLine());
        }
    }

    /**
     * @return Advertisement|null
     * @param int $isValid
     * @throws \Exception
     */

    public function findAllNotValid(int $isValid)
    {

        $query = $this->createQueryBuilder('a')
        ->addSelect('a')
        ->where('a.isValid = :isValid')
        ->setParameter(':isValid', $isValid)
        ->orderBy('a.createdAt', 'desc')
        ->getQuery()
        ;

        try  {
            return $query->getResult();
        }
        catch (\Exception $e) {
            throw new \Exception('Problème dans : ' . $e->getMessage(). $e->getLine());
        }
    }

    /**
     * @return Advertisement|null
     * @param int $isValid
     * @throws \Exception
     */
    public function findByLastAdvertisement(int $isValid)
    {

        $query = $this->createQueryBuilder('a')
        ->addSelect('a')
        ->where('a.isValid = :isValid')
        ->setParameter(':isValid', $isValid)
        ->setMaxResults(3)
        ->orderBy('a.createdAt', 'DESC')
        ->getQuery()
        ;

        try {
            return $query->getResult();
        }
        catch (\Exception $e) {
            throw new \Exception('Problème dans : '. $e->getMessage(). $e->getLine());
        }
    }

    /**
     * @param $user
     * @return Advertisement|null
     * @throws \Exception
     */
    public function findByMyAdvertisement($user)
    {
        //SELECT * FROM `advertisement` WHERE user_id = $user is valid = 1
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->from('App\Entity\Advertisement', 'r')
            ->where('a.user = :user')
            ->orderBy('a.createdAt', 'DESC')
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
     * @return Advertisement|null
     * @param Advertisement $advertisement
     * @param User $from
     * @param User $to
     * @throws \Exception
     */
    public function findByMyMessages(Advertisement $advertisement, User $from, User $to)
    {

        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->from('App\Entity\Advertisement', 'r')
            ->where('a = :advertisement')
            ->leftJoin('a.messages', 'm')
            ->andWhere('m.toId = :to')
            ->andWhere('m.fromId = :from')
            ->orderBy('m.createdAt', 'ASC')
            ->setParameter(':advertisement', $advertisement)
            ->setParameter(':from', $from)
            ->setParameter(':to', $to)
            ->getQuery()
        ;

        try {
            return $query->getResult();
        }
        catch(\Exception $e) {
            throw new \Exception('problème '. $e->getMessage(). $e->getFile());
        }
    }


    /**
     * @return Advertisement|null
     * @param string $args
     * @throws \Exception
     */
    public function findBySearch(string $args)
    //SELECT * FROM `advertisement` WHERE category_id = 7 AND region_id = 1 AND title LIKE '%iph%' OR description LIKE 'test'
    {
        $query = $this->createQueryBuilder('a')
        ->addSelect('a')
        ->where('a.title LIKE :chaine')
        ->setParameter(':chaine', $args)
        ->getQuery();

        try {
            return $query->getResult();
        }
        catch(\Exception $e) {
            throw new \Exception('problème '. $e->getMessage(). $e->getFile());
        }
    }

























}
