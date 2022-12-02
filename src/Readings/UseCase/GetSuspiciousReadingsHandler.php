<?php

namespace Holaluz\Trial\Readings\UseCase;

use Holaluz\Trial\Readings\Domain\Model\Reading;
use Holaluz\Trial\Readings\Domain\Repository\SourceType;
use Holaluz\Trial\Readings\Application\DTO\ReadingResponse;
use Holaluz\Trial\Readings\Application\Adapter\ReadingOutputAdapter;
use Holaluz\Trial\Readings\Domain\Repository\ReadingRepositoryFactory;

class GetSuspiciousReadingsHandler
{
    /**
     * @param ReadingRepositoryFactory $readingRepositoryFactory
     * @param ReadingOutputAdapter     $readingOutputAdapter
     */
    public function __construct(
        private readonly ReadingRepositoryFactory $readingRepositoryFactory,
        private readonly ReadingOutputAdapter     $readingOutputAdapter
    ) {
    }

    /**
     * @param GetSuspiciousReadingsCommand $command
     *
     * @return ReadingResponse[]
     */
    public function handle(GetSuspiciousReadingsCommand $command): array
    {
        $readings = $this->readingRepositoryFactory
            ->get(SourceType::FILE)
            ->findAllSuspicious($command->getSource());
        return array_map(
            fn(Reading $reading) => $this->readingOutputAdapter->transform($reading),
            $readings
        );
    }
}