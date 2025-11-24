<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Serializer\CustomDateIntervalNormalizer;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class DateIntervalFilter extends AbstractFilter
{
    public function __construct(
        private CustomDateIntervalNormalizer $dateIntervalNormalizer,
        ManagerRegistry $managerRegistry,
        ?LoggerInterface $logger = null,
        ?array $properties = null,
        ?NameConverterInterface $nameConverter = null,
    ) {
        parent::__construct($managerRegistry, $logger, $properties, $nameConverter);
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if (
            !$this->isPropertyEnabled($property, $resourceClass) ||
            !$this->isPropertyMapped($property, $resourceClass)
        ) {
            return;
        }

        try {
            $dateInterval = $this->dateIntervalNormalizer->denormalize($value, \DateInterval::class);
        } catch (\Exception $e) {
            return;
        }

        $parameterName = $queryNameGenerator->generateParameterName($property);
        $queryBuilder
            ->andWhere(sprintf('o.%s <= :%s', $property, $parameterName))
            ->setParameter($parameterName, $dateInterval->format('P%yY%mM%dDT%hH%iM%sS'));
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'pretDans' => [
                'property' => 'dateInterval',
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
                'swagger' => [
                    'description' => 'Filter by minimum DateInterval',
                    'type' => 'string',
                    'example' => '30 minutes',
                ],
            ],
        ];
    }
}
