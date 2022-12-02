<?php

namespace Tests\Holaluz\Trial\Readings\Infrastructure\Repository;

use PHPUnit\Framework\TestCase;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\MockObject\MockObject;
use Holaluz\Trial\Readings\Domain\Model\Reading;
use Holaluz\Trial\Readings\Domain\Repository\SourceType;
use Tests\Holaluz\Trial\Readings\Domain\Model\ReadingTest;
use Holaluz\Trial\Shared\Infrastructure\File\Format\AllowedFileFormat;
use Holaluz\Trial\Shared\Infrastructure\File\Parser\ContentParserFactory;
use Holaluz\Trial\Shared\Infrastructure\File\Parser\ContentParserInterface;
use Holaluz\Trial\Readings\Infrastructure\Repository\ReadingsFromFileRepository;
use Holaluz\Trial\Readings\Infrastructure\Repository\Filter\SuspiciousReadingFilter;

class ReadingsFromFileRepositoryTest extends TestCase
{

    private FilesystemOperator&MockObject $filesystemOperator;

    private ContentParserInterface&MockObject $contentParser;

    private ReadingsFromFileRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filesystemOperator = $this->createMock(FilesystemOperator::class);
        $this->contentParser = $this->createMock(ContentParserInterface::class);
        $this->repository = new ReadingsFromFileRepository(
            $this->filesystemOperator,
            new ContentParserFactory([$this->contentParser]),
            new SuspiciousReadingFilter()
        );
    }

    public function testIsAllowedForFileSource(): void
    {
        $this->assertTrue($this->repository->isAllowedForSource(SourceType::FILE));
    }

    public function testFindAllSuspicious(): void
    {
        $source = 'file.csv';
        $content = 'file contents';

        $client1 = '1234';
        $client2 = '4321';
        $suspicious = [
            ReadingTest::newReading(client: $client1, reading: 9),
            ReadingTest::newReading(client: $client1, reading: 999),
            ReadingTest::newReading(client: $client2, reading: 8),
            ReadingTest::newReading(client: $client2, reading: 888),
        ];
        $this->setUpFilesystemExpectations($source, $content);
        $this->setUpContetParserExpectations($content, $this->setUpReadingFixtures($client1, $client2, $suspicious));
        $suspiciousReadings = $this->repository->findAllSuspicious($source);

        $this->assertCount(count($suspicious), $suspiciousReadings);
        foreach ($suspiciousReadings as $suspiciousReading) {
            $this->assertInstanceOf(Reading::class, $suspiciousReading);
            $this->assertTrue(in_array($suspiciousReading, $suspicious));
        }
    }

    /**
     * @param string $source
     * @param string $content
     *
     * @return void
     */
    private function setUpFilesystemExpectations(string $source, string $content): void
    {
        $this->filesystemOperator->expects($this->once())
            ->method('fileExists')
            ->with($source)
            ->willReturn(true);
        $this->filesystemOperator->expects($this->once())
            ->method('read')
            ->with($source)
            ->willReturn($content);
    }

    /**
     * @param string $content
     * @param array  $readings
     *
     * @return void
     */
    private function setUpContetParserExpectations(string $content, array $readings): void
    {
        $this->contentParser->expects($this->once())
            ->method('parsesFormat')
            ->with(AllowedFileFormat::CSV)
            ->willReturn(true);
        $this->contentParser->expects($this->once())
            ->method('parse')
            ->with($content)
            ->willReturn($readings);
    }

    /**
     * @param Reading[] $suspicious
     *
     * @throws \Exception
     * @return Reading[]
     */
    private function setUpReadingFixtures(string $client1, string $client2, array $suspicious): array
    {
        $readings = [];
        for ($i = 0; $i < 20; ++$i) {
            $readings[] = ReadingTest::newReading(
                client:  $i % 2 == 0 ? $client1 : $client2,
                reading: random_int(
                             100,
                             120
                         )
            );
        }
        $readings = array_merge($readings, $suspicious);

        usort($readings, fn(Reading $a, Reading $b) => $a->getClient() <=> $b->getClient());
        return $readings;
    }
}
