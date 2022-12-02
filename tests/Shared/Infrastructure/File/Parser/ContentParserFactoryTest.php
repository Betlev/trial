<?php

namespace Tests\Holaluz\Trial\Shared\Infrastructure\File\Parser;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Holaluz\Trial\Shared\Infrastructure\File\Parser\CsvParser;
use Holaluz\Trial\Shared\Infrastructure\File\Format\AllowedFileFormat;
use Holaluz\Trial\Shared\Infrastructure\File\Parser\ContentParserFactory;

class ContentParserFactoryTest extends TestCase
{
    public function testGet(): void
    {
        $csvParserMock = $this->createMock(CsvParser::class);
        $csvParserMock->expects($this->once())
            ->method('parsesFormat')
            ->with(AllowedFileFormat::CSV)
            ->willReturn(true);
        $contentParsers = [$csvParserMock];
        $factory = new ContentParserFactory($contentParsers);
        $csvParser = $factory->get(AllowedFileFormat::CSV);
        $this->assertInstanceOf(CsvParser::class, $csvParser);
    }

    public function testGetUnknownParserThrowsException(): void
    {
        $factory = new ContentParserFactory([]);
        $this->expectException(InvalidArgumentException::class);
        $factory->get(AllowedFileFormat::CSV);
    }
}
