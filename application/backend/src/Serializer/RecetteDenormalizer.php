<?php

namespace App\Serializer;

use App\Entity\Recette;
use App\Service\DateIntervalNormalizer;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class RecetteDenormalizer implements DenormalizerInterface
{
    private const ALREADY_CALLED = 'RECETTE_OBJECT_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        #[Autowire(service: 'api_platform.jsonld.normalizer.item')]
        private readonly DenormalizerInterface $normalizer,
        private readonly DateIntervalNormalizer $dateIntervalNormalizer,
    ) {}

    /**
     * @return Recette
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $data['pretDans'] = $this->dateIntervalNormalizer->denormalize($data['pretDans']);
        $data['tempsDeCuisson'] = $this->dateIntervalNormalizer->denormalize($data['tempsDeCuisson']);
        $data['tempsDePreparation'] = $this->dateIntervalNormalizer->denormalize($data['tempsDePreparation']);

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $type === Recette::class;
    }
}
