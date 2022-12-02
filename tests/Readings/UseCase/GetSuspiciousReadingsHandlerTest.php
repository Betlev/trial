<?php

namespace Tests\Holaluz\Trial\Readings\UseCase;

use PHPUnit\Framework\TestCase;
use Holaluz\Trial\Readings\Domain\Repository\SourceType;
use Tests\Holaluz\Trial\Readings\Domain\Model\ReadingTest;
use Holaluz\Trial\Readings\Application\DTO\ReadingResponse;
use Holaluz\Trial\Readings\Domain\Repository\ReadingRepository;
use Holaluz\Trial\Readings\UseCase\GetSuspiciousReadingsHandler;
use Holaluz\Trial\Readings\UseCase\GetSuspiciousReadingsCommand;
use Holaluz\Trial\Readings\Application\Adapter\ReadingOutputAdapter;
use Holaluz\Trial\Readings\Domain\Repository\ReadingRepositoryFactory;

class GetSuspiciousReadingsHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $readings = [
            ReadingTest::newReading('1234', '2022-01', 123),
            ReadingTest::newReading('4321', '2022-04', 321),
            ReadingTest::newReading('4231', '2022-06', 231),
            ReadingTest::newReading('2413', '2022-11', 312),
        ];
        $source = 'filename.csv';
        $repository = $this->createMock(ReadingRepository::class);
        $repository->expects($this->once())
            ->method('isAllowedForSource')
            ->with(SourceType::FILE)
            ->willReturn(true);
        $repository->expects($this->once())
            ->method('findAllSuspicious')
            ->with($source)
            ->willReturn($readings);
        $handler = new GetSuspiciousReadingsHandler(
            new ReadingRepositoryFactory([$repository]),
            new ReadingOutputAdapter()
        );
        $command = new GetSuspiciousReadingsCommand($source);
        $readingResponses = $handler->handle($command);
        $this->assertCount(count($readings), $readingResponses);
        foreach ($readingResponses as $idx => $readingResponse) {
            $this->assertInstanceOf(ReadingResponse::class, $readingResponse);
            $this->assertSame($readings[$idx]->getClient(), $readingResponse->getClient());
            $this->assertSame($readings[$idx]->getPeriod()->format('m'), $readingResponse->getPeriod());
            $this->assertSame($readings[$idx]->getReading(), $readingResponse->getReading());
        }
    }
}
