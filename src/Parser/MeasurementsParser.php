<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Parser;

use MStroink\StecaGrid\Resource\Measurements\MeasurementType as Type;

final class MeasurementsParser extends ResponseParser
{
    protected const JS_MAP = [
        'P DC' => Type::DC_POWER,
        'U DC' => Type::DC_VOLTAGE,
        'I DC' => Type::DC_CURRENT,
        'U AC' => Type::AC_VOLTAGE,
        'I AC' => Type::AC_CURRENT,
        'F AC' => Type::AC_FREQUENCY,
        'P AC' => Type::AC_POWER,
    ];

    public static function parse(string $contents): array
    {
        if (substr($contents, 0, 5) === "<?xml") {
            return self::parseXml($contents);
        }

        return self::parseJs($contents);
    }

    private static function parseXml(string $contents): array
    {
        $xml = new \SimpleXMLElement($contents);

        $measurements = [];
        foreach ($xml->Device->Measurements->Measurement as $measurement) {
            $attr = $measurement->attributes();
            $key = strtolower((string) $attr->Type);
            $measurements[$key] = [
                'value' => (float) $attr->Value,
                'unit' => (string) $attr->Unit,
                'type' => (string) $attr->Type,
            ];
        }

        return $measurements;
    }

    private static function parseJs(string $contents): array
    {
        $pattern = "<\/td><td align='right'>\s*(\d+(?:\.\d+)?)<\/td><td>(\w{1,2})<\/td>";

        $measurements = [];
        foreach (self::JS_MAP as $label => $type) {
            $regex = $label . $pattern;
            preg_match("/$regex/", $contents, $match);

            if (!empty($match[1]) && $match[2]) {
                $key = strtolower($type);
                $measurements[$key] = [
                    'value' => (float) $match[1],
                    'unit' => $match[2],
                    'type' => $type,
                ];
            }
        }

        return $measurements;
    }
}
