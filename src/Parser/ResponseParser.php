<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Parser;

abstract class ResponseParser
{
    abstract public static function parse(string $contents): array;
}
