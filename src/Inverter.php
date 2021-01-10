<?php

declare(strict_types=1);

namespace MStroink\StecaGrid;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Utils;
use MStroink\StecaGrid\Exception\HttpClientException;
use MStroink\StecaGrid\Exception\HttpServerException;
use MStroink\StecaGrid\Parser\MeasurementsParser;
use MStroink\StecaGrid\Parser\YieldParser;
use MStroink\StecaGrid\Resource\Measurements\Measurements;
use MStroink\StecaGrid\Resource\Yields\YieldToday;
use Psr\Http\Message\ResponseInterface;
use MStroink\StecaGrid\Resource\InverterResponse;

class Inverter
{
    protected const YIELD_TODAY_ENDPOINT_JS = 'gen.yield.day.chart.js';
    protected const MEASUREMENTS_ENDPOINT_JS = 'gen.measurements.table.js';
    protected const MEASUREMENTS_ENDPOINT_XML = 'measurements.xml';

    protected const DEFAULT_CONFIG = [
        'timeout' => 4,
        'connect_timeout' => 4,
        'http_errors' => false,
    ];
    protected GuzzleClient $Client;
    protected bool $useMeasurementsJs = false;

    public function __construct(GuzzleClient $client)
    {
        $this->Client = $client;
    }

    public static function create(string $host, array $clientConfig = []): self
    {
        $handlerStack = new HandlerStack(Utils::chooseHandler());
        $handlerStack->push(Middleware::prepareBody(), 'prepare_body');

        $config = array_merge(
            self::DEFAULT_CONFIG,
            [
                'handler' => $handlerStack,
                'base_uri' => sprintf('http://%s/', $host)
            ],
            $clientConfig
        );

        return new self(new GuzzleClient($config));
    }

    public function useMeasurementsJs(bool $enable): self
    {
        $this->useMeasurementsJs = $enable;

        return $this;
    }

    protected function call(string $endpoint): ResponseInterface
    {
        try {
            $response = $this->Client->request('GET', $endpoint);
        } catch (TransferException $e) {
            throw HttpServerException::networkError($e);
        }

        return $response;
    }

    /**
     * @deprecated use yieldToday()->getTotal() instead
     */
    public function getDaily(): float
    {
        return $this->getYieldToday()->getTotal();
    }

    public function getYieldToday(): YieldToday
    {
        $response = $this->call(self::YIELD_TODAY_ENDPOINT_JS);

        return $this->hydrateResponse($response, YieldParser::class, YieldToday::class);
    }

    public function getMeasurements(): Measurements
    {
        if ($this->useMeasurementsJs) {
            $response = $this->call(self::MEASUREMENTS_ENDPOINT_JS);
        } else {
            $response = $this->call(self::MEASUREMENTS_ENDPOINT_XML);
        }

        return $this->hydrateResponse($response, MeasurementsParser::class, Measurements::class);
    }

    protected function hydrateResponse(ResponseInterface $response, string $parser, string $resource)
    {
        if ($response->getStatusCode() !== 200) {
            $this->handleErrors($response);
        }

        $body = $response->getBody()->getContents();

        $data = call_user_func($parser . '::parse', $body);
        $resource = call_user_func($resource . '::create', $data);

        return $resource;
    }

    protected function handleErrors(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();
        switch ($statusCode) {
            case 404:
                throw HttpClientException::notFound($response);
            case 500:
                throw HttpServerException::serverError();
        }

        throw HttpServerException::unknownHttpResponseCode($statusCode);
    }
}
