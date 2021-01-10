<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Tests\TestCase\Resource;

use MStroink\StecaGrid\Parser\MeasurementsParser;
use MStroink\StecaGrid\Resource\Measurements\Measurement;
use MStroink\StecaGrid\Tests\TestCase\TestCase;

class MeasurementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testCreate()
    {
        $measurement = Measurement::create([
            'value' => 123.12,
            'unit' => 'V',
            'type' => 'DC_Voltage'
        ]);

        $this->assertSame(123.12, $measurement->getValue());
        $this->assertSame('V', $measurement->getUnit());
        $this->assertSame('DC_Voltage', $measurement->getType());
    }

    public function testToArray()
    {
        $expected = [
            'value' => 123.12,
            'unit' => 'V',
            'type' => 'DC_Voltage'
        ];

        $measurement = Measurement::create($expected);

        $this->assertSame($expected, $measurement->toArray());
    }

    public function testToString()
    {
        $measurement = Measurement::create([
            'value' => 123.12,
            'unit' => 'V',
            'type' => 'DC_Voltage'
        ]);

        $this->assertSame('123.12 V', (string) $measurement);
    }
}
