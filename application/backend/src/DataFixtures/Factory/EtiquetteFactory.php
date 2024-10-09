<?php

namespace App\DataFixtures\Factory;

use App\Entity\Etiquette;
use App\Repository\EtiquetteRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Etiquette>
 *
 * @method        Etiquette|Proxy create(array|callable $attributes = [])
 * @method static Etiquette|Proxy createOne(array $attributes = [])
 * @method static Etiquette|Proxy find(object|array|mixed $criteria)
 * @method static Etiquette|Proxy findOrCreate(array $attributes)
 * @method static Etiquette|Proxy first(string $sortedField = 'id')
 * @method static Etiquette|Proxy last(string $sortedField = 'id')
 * @method static Etiquette|Proxy random(array $attributes = [])
 * @method static Etiquette|Proxy randomOrCreate(array $attributes = [])
 * @method static EtiquetteRepository|RepositoryProxy repository()
 * @method static Etiquette[]|Proxy[] all()
 * @method static Etiquette[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Etiquette[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Etiquette[]|Proxy[] findBy(array $attributes)
 * @method static Etiquette[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Etiquette[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class EtiquetteFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'libelle' => self::faker()->text(24),
            'color' => self::faker()->hexColor(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Etiquette $Etiquette): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Etiquette::class;
    }
}
