<?php

namespace App\DataFixtures\Factory;

use App\Entity\Media;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Media>
 *
 * @method        Media|Proxy create(array|callable $attributes = [])
 * @method static Media|Proxy createOne(array $attributes = [])
 * @method static Media|Proxy find(object|array|mixed $criteria)
 * @method static Media|Proxy findOrCreate(array $attributes)
 * @method static Media|Proxy first(string $sortedField = 'id')
 * @method static Media|Proxy last(string $sortedField = 'id')
 * @method static Media|Proxy random(array $attributes = [])
 * @method static Media|Proxy randomOrCreate(array $attributes = [])
 * @method static Media[]|Proxy[] all()
 * @method static Media[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Media[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Media[]|Proxy[] findBy(array $attributes)
 * @method static Media[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Media[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class MediaFactory extends ModelFactory
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
            'filePath' => 'fixtures/no-image.png',
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Media $Media): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Media::class;
    }
}
