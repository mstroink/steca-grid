<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Parser;

final class YieldParser extends ResponseParser
{
    public static function parse(string $contents): array
    {
        $match = \preg_match("/\(\"labelValueId\"\)\.innerHTML\s=\s\"\s{1,4}(\d+\.?(?:\d+)?)/", $contents, $yield);

        if (!$match) {
            throw new \RuntimeException('Unable to parse yield contents');
        }

        return [
            'total' => (float) $yield[1],
        ];
    }
}
