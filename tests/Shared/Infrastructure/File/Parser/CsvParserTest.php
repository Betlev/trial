<?php

namespace Tests\Holaluz\Trial\Shared\Infrastructure\File\Parser;

use PHPUnit\Framework\TestCase;
use Holaluz\Trial\Readings\Domain\Model\Reading;
use Holaluz\Trial\Shared\Infrastructure\File\Parser\CsvParser;

class CsvParserTest extends TestCase
{

    public function testParse(): void
    {
        $parser = new CsvParser();
        $csv = $this->getCsvContent();
        $result = $parser->parse($csv);
        $this->assertCount(count(explode(PHP_EOL, $csv)) - 1, $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(Reading::class, $item);
        }
    }

    private function getCsvContent(): string
    {
        $csv = [
            'client,period,reading',
            '1,2016-01,302',
            '1,2016-02,295',
            '1,2016-03,23',
            '2,2016-01,51',
            '2,2016-02,44',
            '3,2016-01,112',
            '2,2016-03,46',
            '3,2016-02,127',
            '3,2016-03,999',
        ];

        return implode(PHP_EOL, $csv);
    }
}
