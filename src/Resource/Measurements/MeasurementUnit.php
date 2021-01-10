<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Resource\Measurements;

use MStroink\StecaGrid\Resource\Enum;

final class MeasurementUnit extends Enum
{
    public const VOLTAGE = "V";
    public const AMPERE = "A";
    public const HERTZ = "Hz";
    public const WATT = "W";
    public const TEMP_CELSIUS = "°C";
    public const PERCENTAGE = "%";
}
