<?php

namespace App\Serializer;

use App\Entity\Recette;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RecetteNormalizer implements NormalizerInterface
{
    private const ALREADY_CALLED = 'RECETTE_OBJECT_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        #[Autowire(service: 'api_platform.jsonld.normalizer.item')]
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    /**
     * @param Recette $object
     */
    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        $data = $this->normalizer->normalize($object, $format, $context);

        if ($object->getImage()) {
            $data['image'] = $this
                ->normalizer
                ->normalize($object->getImage(), $format, $context);
        }

        $data['pretDans'] = $this->normalizeDateInterval($object->getPretDans());
        $data['tempsDeCuisson'] = $this->normalizeDateInterval($object->getTempsDeCuisson());
        $data['tempsDePreparation'] = $this->normalizeDateInterval($object->getTempsDePreparation());


        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Recette;
    }

    private function normalizeDateInterval(?\DateInterval $interval): ?string
    {
        if ($interval === null) {
            return null;
        }

        $parts = [];

        if ((int)$interval->format('%a') > 0) {
            $unit = (int) $interval->format('%a') > 1 ? 'jours' : 'jour';
            $parts[] = $interval->format('%a ' . $unit);
        }

        if ((int)$interval->format('%h') > 0) {
            $unit = (int) $interval->format('%h') > 1 ? 'heures' : 'heure';
            $parts[] = $interval->format('%h ' . $unit);
        }

        if ((int)$interval->format('%i') > 0) {
            $unit = (int) $interval->format('%i') > 1 ? 'minutes' : 'minute';
            $parts[] = $interval->format('%i ' . $unit);
        }

        return implode(' ', $parts);
    }
}
