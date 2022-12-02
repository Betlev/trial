<?php

namespace Holaluz\Trial\Shared\Infrastructure\File\Parser;

use Holaluz\Trial\Readings\Domain\Model\Reading;
use Holaluz\Trial\Shared\Infrastructure\File\Format\AllowedFileFormat;

interface ContentParserInterface
{
    /**
     * @param AllowedFileFormat $format
     *
     * @return bool
     */
    public function parsesFormat(AllowedFileFormat $format): bool;

    /**
     * @param string $contents
     *
     * @return Reading[]
     */
    public function parse(string $contents): array;
}