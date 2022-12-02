<?php

namespace Holaluz\Trial\Readings\UseCase;

class GetSuspiciousReadingsCommand
{
    /**
     * @param string $source
     */
    public function __construct(private readonly string $source) {}

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }
}