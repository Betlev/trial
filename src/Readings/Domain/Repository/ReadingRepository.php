<?php

namespace Holaluz\Trial\Readings\Domain\Repository;

use Holaluz\Trial\Readings\Domain\Model\Reading;

interface ReadingRepository
{
    /**
     * @param SourceType $sourceType
     *
     * @return bool
     */
    public function isAllowedForSource(SourceType $sourceType): bool;

    /**
     * @param string $source
     * @return Reading[]
     */
    public function findAllSuspicious(string $source): array;
}