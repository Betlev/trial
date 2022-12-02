<?php

namespace Tests\Holaluz\Trial\Readings\Infrastructure\Repository\Filter;

use PHPUnit\Framework\TestCase;
use Tests\Holaluz\Trial\Readings\Domain\Model\ReadingTest;
use Holaluz\Trial\Readings\Infrastructure\Repository\Filter\SuspiciousReadingFilter;

class SuspiciousReadingFilterTest extends TestCase
{
    public function testFilterUsesMedianOfReadings(): void
    {
        $medianReading = 100;
        $upperBound = $medianReading * SuspiciousReadingFilter::BOUNDARY_UPPER_MULTIPLIER;
        $lowerBound = $medianReading * SuspiciousReadingFilter::BOUNDARY_LOWER_MULTIPLIER;
        $outOfBounds= [
            ReadingTest::newReading(reading: $lowerBound - 1),
            ReadingTest::newReading(reading: $upperBound + 1),
        ];
        $readings = [
            ReadingTest::newReading(reading: $medianReading + 1),
            ReadingTest::newReading(reading: $medianReading + 2),
            ReadingTest::newReading(reading: $medianReading - 1),
            ReadingTest::newReading(reading: $upperBound),
            ReadingTest::newReading(reading: $medianReading),
            ReadingTest::newReading(reading: $lowerBound),
        ];

        $filter = new SuspiciousReadingFilter();
        $filteredReadings = $filter->filter(array_merge($readings,$outOfBounds));
        $this->assertCount(count($outOfBounds), $filteredReadings);
        foreach ($filteredReadings as $idx => $filteredReading) {
            $this->assertSame($outOfBounds[$idx]->getReading(), $filteredReading->getReading());
        }
    }
}
