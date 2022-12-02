<?php

namespace Tests\Holaluz\Trial\Readings\Domain\Model;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Holaluz\Trial\Readings\Domain\Model\Reading;

class ReadingTest extends TestCase
{
    public const TEST_CLIENT = '12345';
    public const TEST_PERIOD = '2016-05';
    public const TEST_READING = 34512;

    private Reading $reading;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reading = self::newReading();

    }

    public function testGetters(): void
    {
        $this->assertSame(self::TEST_CLIENT, $this->reading->getClient());
        $this->assertSame(self::TEST_PERIOD, $this->reading->getPeriod()->format('Y-m'));
        $this->assertSame(self::TEST_READING, $this->reading->getReading());
        $this->assertFalse($this->reading->isSuspicious());
    }

    public function testOutOfBoundsSwitchSuspiciousFlagForUpperBound(): void
    {
        $this->assertFalse($this->reading->isSuspicious());
        $upperBound = 30000;
        $lowerBound = 10000;
        $this->assertGreaterThan($upperBound, $this->reading->getReading());
        $outOfBounds = $this->reading->isOutOfBounds($upperBound, $lowerBound);
        $this->assertTrue($outOfBounds);
        $this->assertTrue($this->reading->isSuspicious());
    }

    public function testOutOfBoundsSwitchSuspiciousFlagForLowerBound(): void
    {
        $this->assertFalse($this->reading->isSuspicious());
        $upperBound = 50000;
        $lowerBound = 35000;
        $this->assertLessThan($lowerBound, $this->reading->getReading());
        $outOfBounds = $this->reading->isOutOfBounds($upperBound, $lowerBound);
        $this->assertTrue($outOfBounds);
        $this->assertTrue($this->reading->isSuspicious());
    }

    public function testOutOfBoundsDoNotMarkSuspiciousIfInsideBounds(): void
    {
        $this->assertFalse($this->reading->isSuspicious());
        $upperBound = 35000;
        $lowerBound = 30000;
        $this->assertLessThan($upperBound, $this->reading->getReading());
        $this->assertGreaterThan($lowerBound, $this->reading->getReading());
        $outOfBounds = $this->reading->isOutOfBounds($upperBound, $lowerBound);
        $this->assertFalse($outOfBounds);
        $this->assertFalse($this->reading->isSuspicious());
    }

    /**
     * @param string|null $client
     * @param string|null $period
     * @param int|null    $reading
     *
     * @return Reading
     */
    public static function newReading(
        ?string $client = null,
        ?string $period = null,
        ?int $reading = null
    ): Reading {
        return new Reading(
            $client ?? self::TEST_CLIENT,
            $period ?? self::TEST_PERIOD,
            $reading ?? self::TEST_READING
        );
    }
}
