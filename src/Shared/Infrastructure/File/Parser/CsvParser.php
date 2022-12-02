<?php

declare(strict_types=1);

namespace Holaluz\Trial\Shared\Infrastructure\File\Parser;

use Holaluz\Trial\Readings\Domain\Model\Reading;
use Holaluz\Trial\Shared\Infrastructure\File\Format\AllowedFileFormat;

class CsvParser implements ContentParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $contents): array
    {
        $csv = array_map('str_getcsv', str_getcsv($contents, PHP_EOL));
        $headers = array_shift($csv);
        return array_map(
            function (array $values) use ($headers) {
                $line = array_combine($headers, $values);
                return new Reading($line['client'], $line['period'], (int) $line['reading']);
            },
            $csv
        );
    }

    /**
     * @inheritDoc
     */
    public function parsesFormat(AllowedFileFormat $format): bool
    {
        return $format === AllowedFileFormat::CSV;
    }
}