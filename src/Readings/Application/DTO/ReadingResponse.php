<?php

declare(strict_types=1);

namespace Holaluz\Trial\Readings\Application\DTO;

class ReadingResponse
{
    /**
     * @param string $client
     * @param string $period
     * @param int    $reading
     * @param bool   $suspicious
     */
    public function __construct(
        private readonly string $client,
        private readonly string $period,
        private readonly int    $reading,
        private readonly bool   $suspicious
    )
    {
    }

    /**
     * @return string
     */
    public function getClient(): string
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getPeriod(): string
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
}