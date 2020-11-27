<?php
declare(strict_types=1);

namespace MStroink\StecaGrid;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\TransferException;
use MStroink\StecaGrid\Exception\HttpClientException;
use MStroink\StecaGrid\Exception\HttpServerException;
use Psr\Http\Message\ResponseInterface;

class Inverter
{
    protected const DAILY_ENDPOINT = 'gen.yield.day.chart.js';
    protected const MEASUREMENTS_ENDPOINT = 'gen.measurements.table.js';

    protected const DEFAULT_CONFIG = [
        'timeout' => 4,
        'connect_timeout' => 4,
    ];

    protected GuzzleClient $Client;

    public function __construct(GuzzleClient $client)
    {
        $this->Client = $client;
    }

    public static function create(string $host, array $clientConfig = []): self
    {
        $config = array_merge(
            self::DEFAULT_CONFIG,
            $clientConfig,
            ['base_uri' => sprintf('http://%s/', $host)]
        );

        return new self(new GuzzleClient($config));
    }

    protected function call(string $endpoint): string
    {
        try {
            $response = $this->Client->request('GET', $endpoint);
        } catch (TransferException $e) {
            throw HttpServerException::networkError($e);
        }

        if ($response->getStatusCode() !== 200) {
            $this->handleErrors($response);
        }

        return (string) $response->getBody();
    }

    public function getDaily(): float
    {
        $payload = $this->call(self::DAILY_ENDPOINT);

        $match = \preg_match("/\(\"labelValueId\"\)\.innerHTML\s=\s\"\s{1,4}(\d+\.?(?:\d+)?)/", $payload, $daily);

        if (!$match) {
            throw new \RuntimeException('Error processing response');
        }

        return (float) $daily[1];
    }

    public function getMeasurements(): array
    {
        $payload = $this->call(self::MEASUREMENTS_ENDPOINT);

        $labels = ['U DC', 'I DC', 'U AC', 'I AC', 'F AC', 'P AC'];

        $data = [];
        foreach ($labels as $label) {
            $regex = $label . "<\/td><td align='right'>\s*(\d+(?:\.\d+)?)";
            preg_match("/$regex/", $payload, $match);

            if (!empty($match[1])) {
                $data[$label] = $match[1];
            }
        }

        return $data;
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
