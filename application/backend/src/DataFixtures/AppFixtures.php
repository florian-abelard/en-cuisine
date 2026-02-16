<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\CategorieFactory;
use App\DataFixtures\Factory\EtiquetteFactory;
use App\DataFixtures\Factory\IngredientFactory;
use App\DataFixtures\Factory\MediaFactory;
use App\DataFixtures\Factory\RealisationFactory;
use App\DataFixtures\Factory\RecetteFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        IngredientFactory::createMany(25);

        EtiquetteFactory::createOne(['libelle' => 'Végétarien', 'color' => '#008000']);
        EtiquetteFactory::createOne(['libelle' => 'Halloween', 'color' => '#ffa500']);
        EtiquetteFactory::createMany(5);

        RecetteFactory::createMany(25);

        $dessert = CategorieFactory::repository()
            ->findOneBy(['libelle' => 'Dessert']);

        RecetteFactory::createOne([
            'libelle' => 'Bûche Finesse',
            'categorie' => $dessert,
            'image' => MediaFactory::createOne(['filePath' => 'fixtures/buche-finesse.jpg']),
        ]);

        RecetteFactory::createOne([
            'libelle' => 'Cookies',
            'categorie' => $dessert,
            'image' => MediaFactory::createOne(['filePath' => 'fixtures/cookies.jpg']),
        ]);

        RealisationFactory::createMany(40);

        $manager->flush();
    }
}
