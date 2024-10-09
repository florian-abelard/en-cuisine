<?php

namespace App\Service;

class DateIntervalNormalizer
{
    public function denormalize(?string $userInterval): ?\DateInterval
    {
        if ($userInterval === null) {
            return null;
        }

        $userInterval = $this->cleanDateInterval($userInterval);

        return \DateInterval::createFromDateString($userInterval) ?: null;
    }

    private function cleanDateInterval(string $userInterval): string
    {
        $userInterval = strtolower($userInterval);
        $userInterval = preg_replace('/(\d+)([a-z])/', '$1 $2', $userInterval);
        $userInterval = preg_replace(['/jours$/', '/jour$/', '/j$/'], 'days', $userInterval);
        $userInterval = preg_replace(['/heures$/', '/heure$/', '/h$/'], 'hours', $userInterval);
        $userInterval = preg_replace(['/min$/', '/m$/'], 'minutes', $userInterval);

        return $userInterval;
    }
}
