<?php

declare(strict_types=1);

namespace Holaluz\Trial\Shared\Infrastructure\File\Parser;

use Holaluz\Trial\Readings\Domain\Model\Reading;
use Holaluz\Trial\Shared\Infrastructure\File\Format\AllowedFileFormat;

class XmlParser implements ContentParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $contents): array
    {
        $xml = new \DOMDocument();
        $xml->loadXML($contents);
        $readings = $xml->getElementsByTagName('reading')->getIterator();
        return array_map(
            fn(\DOMNode $reading) =>
                 new Reading(
                    (string) $reading->attributes?->getNamedItem('clientID')?->textContent,
                    (string) $reading->attributes?->getNamedItem('period')?->textContent,
                    (int) $reading->textContent
                )
            , iterator_to_array($readings)
        );
    }

    /**
     * @inheritDoc
     */
    public function parsesFormat(AllowedFileFormat $format): bool
    {
        return $format === AllowedFileFormat::XML;
    }
}