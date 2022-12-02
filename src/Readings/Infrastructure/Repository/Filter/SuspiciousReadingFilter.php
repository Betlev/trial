<?php

declare(strict_types=1);

namespace Holaluz\Trial\Readings\Infrastructure\Repository\Filter;

use Holaluz\Trial\Readings\Domain\Model\Reading;

class SuspiciousReadingFilter
{
    public const BOUNDARY_UPPER_MULTIPLIER = 1.5;
    public const BOUNDARY_LOWER_MULTIPLIER = 0.5;

    /**
     * @param Reading[] $readings
     *
     * @return Reading[]
     */
    public function filter(array $readings): array
    {
        [$upperBound, $lowerBound] = $this->getReadingBoundaries($readings);
        return array_values(
            array_filter(
                $readings,
                fn(Reading $reading) => $reading->isOutOfBounds($upperBound, $lowerBound)
            )
        );
    }

    /**
     * @param Reading[] $readings
     *
     * @return float[]
     */
    private function getReadingBoundaries(array $readings): array
    {
        usort($readings, fn(Reading $a, Reading $b) => $a->getReading() <=> $b->getReading());
        $index = (int) floor(count($readings) / 2) - 1;
        $median = (float) $readings[$index]->getReading();
        return [$median * self::BOUNDARY_UPPER_MULTIPLIER, $median * self::BOUNDARY_LOWER_MULTIPLIER];
    }
}