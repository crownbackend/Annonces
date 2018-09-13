<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use App\Entity\Category;
use App\Entity\Region;
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
        $populator->addEntity(Category::class, 2);
        $populator->addEntity(Region::class, 22);
        $populator->addEntity(Advertisement::class, 150, [
            "price" => function() use ($generator) {
                return $generator->randomNumber();
            },
            "imageName" => function() { return 'hamac.jpg'; },
            "imageName2" => function() { return 'hamac.jpg'; },
            "imageName3" => function() { return 'hamac.jpg'; },
            "imageName4" => function() { return 'hamac.jpg'; }
        ]);
        $populator->execute();

        $manager->flush();
    }
}
