<?php

namespace Tests\Holaluz\Trial\Readings\Domain\Repository;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Holaluz\Trial\Readings\Domain\Repository\SourceType;
use Holaluz\Trial\Readings\Domain\Repository\ReadingRepository;
use Holaluz\Trial\Readings\Domain\Repository\ReadingRepositoryFactory;

class ReadingRepositoryFactoryTest extends TestCase
{
    public function testGet(): void
    {
        $readingRepository = $this->createMock(ReadingRepository::class);
        $readingRepository->expects($this->once())
            ->method('isAllowedForSource')
            ->with(SourceType::FILE)
            ->willReturn(true);
        $factory = new ReadingRepositoryFactory([$readingRepository]);
        $repository = $factory->get(SourceType::FILE);
        $this->assertInstanceOf(ReadingRepository::class, $repository);
    }

    public function testGetWithNoMatchForSourceTypeThrowsException(): void
    {
        $factory = new ReadingRepositoryFactory([]);
        $this->expectException(InvalidArgumentException::class);
        $factory->get(SourceType::FILE);
    }
}
