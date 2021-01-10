<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Resource\Measurements;

use MStroink\StecaGrid\Resource\Enum;

final class MeasurementType extends Enum
{
    public const DC_POWER = 'DC_Power';
    public const DC_VOLTAGE = 'DC_Voltage';
    public const DC_CURRENT = 'DC_Current';
    public const AC_VOLTAGE = 'AC_Voltage';
    public const AC_CURRENT = 'AC_Current';
    public const AC_FREQUENCY = 'AC_Frequency';
    public const AC_POWER = 'AC_Power';
    public const TEMP = 'Temp';
    // public const LINK_VOLTAGE = 'LINK_Voltage';
    // public const GRID_POWER = 'GridPower';
    // public const GRID_CONSUMED_POWER = 'GridConsumedPower';
    // public const GRID_INJECTED_POWER = 'GridInjectedPower';
    // public const OWN_CONSUMEND_POWER = 'OwnConsumedPower';
    // public const DERATING = 'Derating';
}
