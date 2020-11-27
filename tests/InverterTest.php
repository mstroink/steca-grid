<?php

namespace MStroink\StecaGrid\Test;

use MStroink\StecaGrid\Inverter;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class InverterTest extends TestCase
{
    protected MockHandler $MockHandler;
    protected Inverter $Inverter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->MockHandler = new MockHandler();
        $handler = HandlerStack::create($this->MockHandler);
        $client = new GuzzleClient(['handler' => $handler]);
        $this->Inverter = new Inverter($client);
    }

    public function testShouldReturnDaily()
    {
        $body = file_get_contents(dirname(__FILE__) . '/Fixture/gen.yield.day.chart.js');
        $this->MockHandler->append(new Response(200, [], $body));

        $result = $this->Inverter->getDaily();

        $this->assertEquals('12.570', $result);
    }

    public function testShouldReturnMeasurements()
    {
        $body = file_get_contents(dirname(__FILE__) . '/Fixture/gen.measurements.table.js');
        $this->MockHandler->append(new Response(200, [], $body));

        $result = $this->Inverter->getMeasurements();

        $expected = [
            'U DC' => '351.99',
            'I DC' => '0.55',
            'U AC' => '218.21',
            'I AC' => '0.95',
            'F AC' => '50.03',
            'P AC' => '185.72',
        ];

        $this->assertEquals($expected, $result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->Inverter, $this->mockHandler);
    }
}
