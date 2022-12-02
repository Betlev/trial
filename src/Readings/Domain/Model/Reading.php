<?php

declare(strict_types=1);

namespace Holaluz\Trial\Readings\Domain\Model;

use DateTimeImmutable;

class Reading
{
    /**
     * @var string
     */
    private string $client;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $period;

    /**
     * @var int
     */
    private int $reading;

    /**
     * @var bool
     */
    private bool $suspicious;

    /**
     * @param string $client
     * @param string $period
     * @param int    $reading
     */
    public function __construct(string $client, string $period, int $reading)
    {
        $this->client = $client;
        $this->period = DateTimeImmutable::createFromFormat('Y-m', $period);
        $this->reading = $reading;
        $this->suspicious = false;
    }

    /**
     * @return string
     */
    public function getClient(): string
    {
        return $this->client;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getPeriod(): DateTimeImmutable
    {
        return $this->period;
    }

    /**
     * @return int
     */
    public function getReading(): int
    {
        return $this->reading;
    }

    /**
     * @return bool
     */
    public function isSuspicious(): bool
    {
        return $this->suspicious;
    }

    /**
     * @param float $upperBound
     * @param float $lowerBound
     *
     * @return bool
     */
    public function isOutOfBounds(float $upperBound, float $lowerBound): bool
    {
        $outOfBounds = $this->getReading() > $upperBound || $this->getReading() < $lowerBound;
        $this->suspicious = $outOfBounds;
        return $outOfBounds;
    }
}