<?php

namespace App\DataFixtures\Factory;

use App\Entity\Realisation;
use App\Repository\RealisationRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Realisation>
 *
 * @method        Realisation|Proxy create(array|callable $attributes = [])
 * @method static Realisation|Proxy createOne(array $attributes = [])
 * @method static Realisation|Proxy find(object|array|mixed $criteria)
 * @method static Realisation|Proxy findOrCreate(array $attributes)
 * @method static Realisation|Proxy first(string $sortedField = 'id')
 * @method static Realisation|Proxy last(string $sortedField = 'id')
 * @method static Realisation|Proxy random(array $attributes = [])
 * @method static Realisation|Proxy randomOrCreate(array $attributes = [])
 * @method static RealisationRepository|RepositoryProxy repository()
 * @method static Realisation[]|Proxy[] all()
 * @method static Realisation[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Realisation[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Realisation[]|Proxy[] findBy(array $attributes)
 * @method static Realisation[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Realisation[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class RealisationFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'recette' => RecetteFactory::random(),
            'date' => self::faker()->dateTimeBetween('-1 year', 'now'),
            'notes' => self::faker()->boolean(60)
                ? self::faker()->text(150)
                : null,
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return Realisation::class;
    }
}
