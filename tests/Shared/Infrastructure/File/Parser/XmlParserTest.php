<?php

namespace Tests\Holaluz\Trial\Shared\Infrastructure\File\Parser;

use PHPUnit\Framework\TestCase;
use Holaluz\Trial\Readings\Domain\Model\Reading;
use Holaluz\Trial\Shared\Infrastructure\File\Parser\XmlParser;

class XmlParserTest extends TestCase
{

    public function testParse(): void
    {
        $parser = new XmlParser();
        $result = $parser->parse($this->getXmlContent());
        $this->assertCount(6, $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(Reading::class, $item);
        }
    }


    private function getXmlContent(): string
    {
        $xml = <<<XML
<?xml version="1.0"?>
<readings>
	<reading clientID="1" period="2016-01">112</reading>
	<reading clientID="1" period="2016-02">124</reading>
	<reading clientID="1" period="2016-01">37</reading>
	<reading clientID="4" period="2016-01">109</reading>
	<reading clientID="4" period="2016-02">109</reading>
	<reading clientID="4" period="2016-02">105</reading>
</readings>
XML;
        return $xml;
    }
}
