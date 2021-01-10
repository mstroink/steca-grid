<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Tests\TestCase\Parser;

use MStroink\StecaGrid\Parser\YieldParser;
use MStroink\StecaGrid\Tests\TestCase\TestCase;
use RuntimeException;

class YieldParserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testParse()
    {
        $expected = [
            'total' => 12.570
        ];

        $contents = $this->getFixtureFileContents('gen.yield.day.chart.js');
        $data = YieldParser::parse($contents);

        $this->assertSame($expected, $data);
    }

    public function testParseInvalidContents()
    {
        $this->expectException(RuntimeException::class);

        $contents = "invalid";
        YieldParser::parse($contents);
    }
}
