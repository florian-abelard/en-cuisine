<?php

namespace App\Serializer;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\MapDecorated;
use Symfony\Component\Serializer\Normalizer\DateIntervalNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsDecorator(decorates: DateIntervalNormalizer::class)]
class CustomDateIntervalNormalizer implements NormalizerInterface, DenormalizerInterface
{
     public function __construct(
        #[MapDecorated] private DateIntervalNormalizer $inner,
    ) {}

    /**
     * @param \DateInterval $object
     *
     * @return string
     */
    public function normalize(mixed $object, string $format = null, array $context = []):  array|string|int|float|bool|\ArrayObject|null
    {
        if ($object === null) {
            return null;
        }

        $parts = [];

        if ((int)$object->format('%a') > 0) {
            $unit = (int) $object->format('%a') > 1 ? 'jours' : 'jour';
            $parts[] = $object->format('%a ' . $unit);
        }

        if ((int)$object->format('%h') > 0) {
            $unit = (int) $object->format('%h') > 1 ? 'heures' : 'heure';
            $parts[] = $object->format('%h ' . $unit);
        }

        if ((int)$object->format('%i') > 0) {
            $unit = (int) $object->format('%i') > 1 ? 'minutes' : 'minute';
            $parts[] = $object->format('%i ' . $unit);
        }

        return implode(' ', $parts);
    }

    /**
     * @param string $data the date interval on the format "X jours, Y heures, Z minutes"
     *
     * @return \DateInterval
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        if ($data === null) {
            return null;
        }

        $userInterval = $this->cleanDateInterval($data);

        return \DateInterval::createFromDateString($userInterval) ?: null;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $this
            ->inner
            ->supportsNormalization($data, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $this
            ->inner
            ->supportsDenormalization($data, $type, $format, $context);
    }

    private function cleanDateInterval(string $userInterval): string
    {
        $userInterval = strtolower($userInterval);
        $userInterval = preg_replace('/(\d+)([a-z])/', '$1 $2', $userInterval);
        $userInterval = preg_replace(['/jours$/', '/jour$/', '/j$/'], 'days', $userInterval);
        $userInterval = preg_replace(['/jours /', '/jour /', '/j /'], 'days ', $userInterval);
        $userInterval = preg_replace(['/heures$/', '/heure$/', '/h$/'], 'hours', $userInterval);
        $userInterval = preg_replace(['/heures /', '/heure /', '/h /'], 'hours ', $userInterval);
        $userInterval = preg_replace(['/min$/', '/m$/'], 'minutes', $userInterval);

        return $userInterval;
    }
}
