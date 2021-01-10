<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Test\TestCase;

use MStroink\StecaGrid\Inverter;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MStroink\StecaGrid\Exception\HttpClientException;
use MStroink\StecaGrid\Exception\HttpServerException;
use MStroink\StecaGrid\Resource\Measurements\Measurements;
use MStroink\StecaGrid\Resource\InverterResponse;
use MStroink\StecaGrid\Tests\TestCase\TestCase;

class InverterTest extends TestCase
{
    protected MockHandler $MockHandler;
    protected Inverter $Inverter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->MockHandler = new MockHandler();
        $handler = HandlerStack::create($this->MockHandler);
        $client = new GuzzleClient([
            'handler' => $handler,
            'http_errors' => false
        ]);
        $this->Inverter = new Inverter($client);
    }

    public function testShouldReturnYieldToday()
    {
        $body = $this->getFixtureFileContents('gen.yield.day.chart.js');
        $this->MockHandler->append(new Response(200, [], $body));

        $result = $this->Inverter->getYieldToday();

        $this->assertEquals(12.570, $result->getTotal());
    }

    public function testShouldReturnDaily()
    {
        $body = $this->getFixtureFileContents('gen.yield.day.chart.js');
        $this->MockHandler->append(new Response(200, [], $body));

        $result = $this->Inverter->getDaily();

        $this->assertEquals(12.570, $result);
    }

    public function testShouldReturnMeasurementsFromJs()
    {
        $body = $this->getFixtureFileContents('gen.measurements.table.js');
        $this->Inverter->useMeasurementsJs(true);

        $this->MockHandler->append(new Response(200, [], $body));

        $result = $this->Inverter->getMeasurements();

        $this->assertInstanceOf(InverterResponse::class, $result);
        $this->assertInstanceOf(Measurements::class, $result);
    }

    public function testShouldReturnMeasurementsFromXml()
    {
        $body = $this->getFixtureFileContents('measurements.xml');

        $this->MockHandler->append(new Response(200, [], $body));

        $result = $this->Inverter->getMeasurements();

        $this->assertInstanceOf(InverterResponse::class, $result);
        $this->assertInstanceOf(Measurements::class, $result);
    }

    public function testShouldThrowsNotFoundException()
    {
        $this->expectException(HttpClientException::class);
        $this->MockHandler->append(new Response(404, [], ''));

        $this->Inverter->getMeasurements();
    }

    public function testShouldThrowsServerError()
    {
        $this->expectException(HttpServerException::class);
        $this->MockHandler->append(new Response(500, [], ''));

        $this->Inverter->getMeasurements();
    }

    public function testShouldThrowsUnkownError()
    {
        $this->expectException(HttpServerException::class);
        $this->MockHandler->append(new Response(444, [], ''));

        $this->Inverter->getMeasurements();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->Inverter, $this->MockHandler);
    }
}
