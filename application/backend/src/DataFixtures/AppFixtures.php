<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\RecetteFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        RecetteFactory::createOne(['libelle' => 'Cookies']);
        RecetteFactory::createMany(5);

        $manager->flush();
    }
}
