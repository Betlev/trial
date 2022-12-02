<?php

namespace Holaluz\Trial\Readings\Domain\Repository;

use InvalidArgumentException;

class ReadingRepositoryFactory
{
    /**
     * @param iterable<int, ReadingRepository> $repositories
     */
    public function __construct(private readonly iterable $repositories) {}

    /**
     * @param SourceType $sourceType
     *
     * @return ReadingRepository
     */
    public function get(SourceType $sourceType): ReadingRepository
    {
        foreach ($this->repositories as $repository) {
            if ($repository->isAllowedForSource($sourceType)) {
                return $repository;
            }
        }
        throw new InvalidArgumentException(sprintf('no repository found for type %s', $sourceType->value));
    }

}