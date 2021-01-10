<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Tests\TestCase\Resource;

use MStroink\StecaGrid\Parser\MeasurementsParser;
use MStroink\StecaGrid\Resource\Measurements\Measurement;
use MStroink\StecaGrid\Resource\Measurements\Measurements;
use MStroink\StecaGrid\Tests\TestCase\TestCase;

class MeasurementsTest extends TestCase
{
    protected Measurements $measurments;

    protected function setUp(): void
    {
        $contents = $this->getFixtureFileContents('measurements.xml');
        $data = MeasurementsParser::parse($contents);
        $this->measurments = Measurements::create($data);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        unset($this->measurments);

        parent::tearDown();
    }

    public function testGetMeasurement()
    {
        $methods = [
            'getDcPower', 'getDcVoltage', 'getAcVoltage', 'getDcCurrent',
            'getAcVoltage', 'getAcCurrent', 'getAcFrequency', 'getAcPower',
        ];

        foreach ($methods as $method) {
            $this->assertInstanceOf(
                Measurement::class,
                $this->measurments->{$method}(),
                "$method() is not instance of class Measurements"
            );
        }

        $this->assertSame(370.829, $this->measurments->getDcVoltage()->getValue());
        $this->assertSame('V', $this->measurments->getDcVoltage()->getUnit());
        $this->assertSame('DC_Voltage', $this->measurments->getDcVoltage()->getType());
    }
    
    public function testToArray()
    {
        $expected = [
            'value' => 370.829,
            'unit' => 'V',
            'type' => 'DC_Voltage',
        ];

        $result = $this->measurments->toArray();
        $this->assertIsArray($result);
        $this->assertCount(8, $result);

        $this->assertArrayHasKey('temp', $result);
        $this->assertSame($expected, $result['dc_voltage'] ?? null);
    }

    public function testToArrayFilterNulls()
    {
        $measurements = Measurements::create([
            'dc_voltage' => [
                'value' => 370.829,
                'unit' => 'V',
                'type' => 'DC_Voltage',
            ]
        ]);

        $expected = [
            'value' => 370.829,
            'unit' => 'V',
            'type' => 'DC_Voltage',
        ];

        $result = $measurements->toArray();
        $this->assertCount(1, $result);
        $this->assertSame($expected, $result['dc_voltage'] ?? null);
    }

    public function testToList()
    {
        $expected = 370.829;

        $result = $this->measurments->toList();
        $this->assertIsArray($result);
        $this->assertCount(8, $result);

        $this->assertArrayHasKey('temp', $result);
        $this->assertSame($expected, $result['dc_voltage'] ?? null);
    }
}
