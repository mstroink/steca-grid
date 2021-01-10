<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Tests\TestCase\Parser;

use MStroink\StecaGrid\Parser\MeasurementsParser;
use MStroink\StecaGrid\Tests\TestCase\TestCase;

class MeasurementsParserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testParseXml()
    {
        $expected = [
            'value' => 223.487,
            'unit' => 'V',
            'type' => 'AC_Voltage'
        ];

        $contents = $this->getFixtureFileContents('measurements.xml');
        $result = MeasurementsParser::parse($contents);

        $this->assertSame($expected, $result['ac_voltage'] ?? null);
        $this->assertCount(8, $result);
    }

    public function testParseJs()
    {
        $expected = [
            'dc_power' => [
                'value' => 192.37,
                'unit' => 'W',
                'type' => 'DC_Power',
            ],
            'dc_voltage' => [
                'value' => 351.99,
                'unit' => 'V',
                'type' => 'DC_Voltage'
            ],
            'dc_current' => [
                'value' => 0.55,
                'unit' => 'A',
                'type' => 'DC_Current'
            ],
            'ac_voltage' => [
                'value' => 218.21,
                'unit' => 'V',
                'type' => 'AC_Voltage',
            ],
            'ac_current' => [
                'value' => 0.95,
                'unit' => 'A',
                'type' => 'AC_Current',
            ],
            'ac_frequency' => [
                'value' => 50.03,
                'unit' => 'Hz',
                'type' => 'AC_Frequency',
            ],
            'ac_power' => [
                'value' => 185.72,
                'unit' => 'W',
                'type' => 'AC_Power',
            ],
        ];

        $contents = $this->getFixtureFileContents('gen.measurements.table.js');
        $result = MeasurementsParser::parse($contents);

        $this->assertSame($expected, $result ?? null);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
