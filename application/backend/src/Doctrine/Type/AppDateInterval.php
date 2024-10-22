<?php

declare(strict_types=1);

namespace App\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

final class AppDateInterval extends Type
{
    public const NAME = 'app_dateinterval';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateInterval) {
            return $value->format('P%yY%mM%dDT%hH%iM%sS');
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', \DateInterval::class],
        );
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?\DateInterval
    {
        if ($value === null) {
            return null;
        }

        try {
            list($hours, $minutes, $seconds) = explode(':', $value);
            return new \DateInterval(sprintf('PT%dH%dM%dS', $hours, $minutes, $seconds));
        } catch (\Exception $e) {
            throw ConversionException::conversionFailed(
                $value,
                $this->getName(),
                $e,
            );
        }
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getName(): string
    {
        return self::NAME;
    }
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "INTERVAL";
    }
}
