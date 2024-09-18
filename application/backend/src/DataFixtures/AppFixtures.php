<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\CategorieFactory;
use App\DataFixtures\Factory\RecetteFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $dessert = CategorieFactory::repository()
            ->findOneBy(['libelle' => 'Dessert']);

        RecetteFactory::createOne([
            'libelle' => 'Bûche Finesse',
            'categorie' => $dessert,
        ]);

        RecetteFactory::createMany(25);

        $manager->flush();
    }
}
