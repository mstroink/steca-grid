<?php

namespace mstroink\StecaGrid\Test;

use mstroink\StecaGrid\Inverter;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class InverterTest extends TestCase
{
    public function testShouldReturnDaily()
    {
        $body = file_get_contents(dirname(__FILE__) . '/gen.yield.day.chart.js');
        $inverter = $this->getInverter(200, $body);
        $result = $inverter->getDaily();

        $this->assertEquals('12.570', $result);
    }

    public function testShouldReturnMeasurements()
    {
        $body = file_get_contents(dirname(__FILE__) . '/gen.measurements.table.js');
        $inverter = $this->getInverter(200, $body);
        $result = $inverter->getMeasurements();
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

    private function getInverter($status, $body = null)
    {
        $mock = new MockHandler([new Response($status, [], $body)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return new Inverter([], $client);
    }
}
