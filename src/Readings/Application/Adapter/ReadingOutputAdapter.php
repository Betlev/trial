<?php

declare(strict_types=1);

namespace Holaluz\Trial\Readings\Application\Adapter;

use Holaluz\Trial\Readings\Domain\Model\Reading;
use Holaluz\Trial\Readings\Application\DTO\ReadingResponse;

class ReadingOutputAdapter
{
    /**
     * @param Reading $reading
     *
     * @return ReadingResponse
     */
    public function transform(Reading $reading): ReadingResponse
    {
        return new ReadingResponse(
            $reading->getClient(),
            $reading->getPeriod()->format('m'),
            $reading->getReading(),
            $reading->isSuspicious()
        );
    }
}