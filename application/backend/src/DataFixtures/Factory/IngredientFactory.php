<?php

namespace App\DataFixtures\Factory;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Ingredient>
 *
 * @mixin Restaurant
 *
 * @method        Ingredient|Proxy create(array|callable $attributes = [])
 * @method static Ingredient|Proxy createOne(array $attributes = [])
 * @method static Ingredient|Proxy find(object|array|mixed $criteria)
 * @method static Ingredient|Proxy findOrCreate(array $attributes)
 * @method static Ingredient|Proxy first(string $sortedField = 'id')
 * @method static Ingredient|Proxy last(string $sortedField = 'id')
 * @method static Ingredient|Proxy random(array $attributes = [])
 * @method static Ingredient|Proxy randomOrCreate(array $attributes = [])
 * @method static IngredientRepository|RepositoryProxy repository()
 * @method static Ingredient[]|Proxy[] all()
 * @method static Ingredient[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Ingredient[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Ingredient[]|Proxy[] findBy(array $attributes)
 * @method static Ingredient[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Ingredient[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class IngredientFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
        self::faker()->addProvider(new Restaurant(self::faker()));
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'libelle' => $this->generateLibelle(),
        ];
    }

    private function generateLibelle(): string
    {
        $faker = self::faker();
        $options = [
            $faker->vegetableName(),
            $faker->meatName(),
            $faker->fruitName(),
            $faker->dairyName(),
        ];

        return $faker->randomElement($options);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Ingredient $Ingredient): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Ingredient::class;
    }
}
