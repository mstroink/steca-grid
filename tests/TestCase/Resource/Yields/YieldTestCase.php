<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Tests\TestCase\Resource\Yields;

use InvalidArgumentException;
use MStroink\StecaGrid\Resource\Yields\YieldToday;
use MStroink\StecaGrid\Tests\TestCase\TestCase;

class YieldTestCase extends TestCase
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
        $yieldToday = YieldToday::create([
            'total' => '12.34',
        ]);

        $this->assertSame(12.34, $yieldToday->getTotal());
        $this->assertSame('kWh', $yieldToday->getUnit());
    }

    public function testCreateWithEmptyDataThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        YieldToday::create([]);
    }

    public function testToString()
    {
        $yieldToday = YieldToday::create([
            'total' => '12.34',
        ]);

        $this->assertSame('12.34 kWh', (string) $yieldToday);
    }

    public function testToArray()
    {
        $yieldToday = YieldToday::create([
            'total' => '12.34',
        ]);

        $this->assertSame(['total' => 12.34], $yieldToday->toArray());
    }
}
