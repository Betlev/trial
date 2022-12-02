<?php

declare(strict_types=1);

namespace Holaluz\Trial\Shared\Infrastructure\File\Parser;

use InvalidArgumentException;
use Holaluz\Trial\Shared\Infrastructure\File\Format\AllowedFileFormat;

class ContentParserFactory
{
    /**
     * @param iterable<int, ContentParserInterface> $contentParsers
     */
    public function __construct(private readonly iterable $contentParsers) {}

    /**
     * @param AllowedFileFormat $format
     *
     * @return ContentParserInterface
     * @throws InvalidArgumentException
     */
    public function get(AllowedFileFormat $format): ContentParserInterface
    {
        foreach ($this->contentParsers as $contentParser) {
            if ($contentParser->parsesFormat($format)) {
                return $contentParser;
            }
        }
        throw new InvalidArgumentException(sprintf('No content parser found for format %s', $format->value));
    }
}