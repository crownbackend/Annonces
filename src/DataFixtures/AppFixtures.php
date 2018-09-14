<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use App\Entity\Category;
use App\Entity\Region;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\ORM\Doctrine\Populator;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $generator = \Faker\Factory::create('fr_FR');

        $populator = new Populator($generator, $manager);
        $populator->addEntity(User::class, 10);
        $populator->addEntity(Category::class, 5);
        $populator->addEntity(Region::class, 22);
        $populator->addEntity(Advertisement::class, 200, [
            'address' => function() use ($generator) { return $generator->address; },
            'description' => $generator->text(2000),
            'price' => mt_getrandmax(),
            'imageName' => function() use ($generator) { return 'https://picsum.photos/1000/700'; },
            'imageName2' => function() use ($generator) { return 'https://picsum.photos/1000/700'; },
            'imageName3' => function() use ($generator) { return 'https://picsum.photos/1000/700'; },
            'imageName4' => function() use ($generator) { return 'https://picsum.photos/1000/700'; },
        ]);
        $populator->execute();
        $manager->flush();
    }
}
