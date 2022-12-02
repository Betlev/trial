<?php

namespace Holaluz\Trial\Readings\Infrastructure\Repository;

use League\Flysystem\FilesystemOperator;
use League\Flysystem\FilesystemException;
use Holaluz\Trial\Readings\Domain\Repository\SourceType;
use Holaluz\Trial\Readings\Domain\Repository\ReadingRepository;
use Holaluz\Trial\Shared\Infrastructure\File\Format\AllowedFileFormat;
use Holaluz\Trial\Shared\Infrastructure\File\Parser\ContentParserFactory;
use Holaluz\Trial\Readings\Infrastructure\Repository\Filter\SuspiciousReadingFilter;

class ReadingsFromFileRepository implements ReadingRepository
{
    public const READINGS_PER_CLIENT = 12;

    /**
     * @param FilesystemOperator      $localStorage
     * @param ContentParserFactory    $contentParserFactory
     * @param SuspiciousReadingFilter $suspiciousReadingFilter
     */
    public function __construct(
        private readonly FilesystemOperator   $localStorage,
        private readonly ContentParserFactory $contentParserFactory,
        private readonly SuspiciousReadingFilter $suspiciousReadingFilter
    ) {}

    /**
     * @inheritDoc
     */
    public function isAllowedForSource(SourceType $sourceType): bool
    {
        return $sourceType === SourceType::FILE;
    }

    /**
     * @inheritDoc
     * @throws FilesystemException
     */
    public function findAllSuspicious(string $source): array
    {
        if (!$this->localStorage->fileExists($source)) {
            throw new \RuntimeException(sprintf('File %s not found on local storage', $source));
        }
        $fileFormat = $this->getFileFormat($source);
        $contentParser = $this->contentParserFactory->get($fileFormat);
        $content = $this->localStorage->read($source);
        $readings = $contentParser->parse($content);
        $readingsPerClient = array_chunk($readings, self::READINGS_PER_CLIENT);
        $suspiciousReadingsPerClient = array_filter(
            array_map(
                fn(array $readings) => $this->suspiciousReadingFilter->filter($readings),
                $readingsPerClient
            )
        );
        return array_merge(...$suspiciousReadingsPerClient);
    }

    /**
     * @param string $source
     *
     * @return AllowedFileFormat
     */
    private function getFileFormat(string $source): AllowedFileFormat
    {
        foreach (AllowedFileFormat::cases() as $fileFormat) {
            if (str_ends_with($source, sprintf('.%s', $fileFormat->value))) {
                return $fileFormat;
            }
        }
        throw new \InvalidArgumentException(
            sprintf(
                'File format not recognized for file: %s. Only formats [%s] allowed.',
                $source,
                implode(', ', AllowedFileFormat::cases())
            )
        );
    }
}