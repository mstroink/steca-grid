<?php
namespace mstroink\StecaGrid;

use GuzzleHttp\Client;

class Inverter
{
    protected $_defaultConfig = [
        'timeout' => 4,
        'host' => '192.168.1.164',
    ];

    private $errors = [];

    protected $Client;

    public function __construct($config = [], $client = null)
    {
        $this->config = array_merge($this->_defaultConfig, $config);
        if ($client == null) {
            $client = $this->getClient();
        }

        $this->Client = $client;
    }

    protected function call(string $endpoint)
    {
        try {
            $payload = (string)$this->Client->request('GET', $endpoint)->getBody();
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $this->errors[] = $e;
            //inventer could be offline or timeout. Do nothing.
        }

        return $payload ?? false;
    }

    public function getDaily()
    {
        $payload = $this->call('gen.yield.day.chart.js');

        if (!$payload) {
            return false;
        }

        preg_match("/\(\"labelValueId\"\)\.innerHTML\s=\s\"\s{1,4}(\d+\.?(?:\d+)?)/", $payload, $daily);

        return $daily[1];
    }

    public function getMeasurements()
    {
        $payload = $this->call('gen.measurements.table.js');

        if (!$payload) {
            return false;
        }
        
        $labels = ['U DC', 'I DC', 'U AC', 'I AC', 'F AC', 'P AC'];

        $data = [];
        foreach($labels as $label) {
            $regex = $label . "<\/td><td align='right'>\s*(\d+(?:\.\d+)?)";
            preg_match("/$regex/", $payload, $match);

            if (!empty($match[1])) {
                $data[$label] = $match[1];
            }
        }

        return $data;
    }

    protected function getClient()
    {
        return new Client([
            'base_uri' => sprintf('http://%s/', $this->config['host']),
            'timeout' => $this->config['timeout'],
            'connect_timeout' => $this->config['timeout']
        ]);
    }

    public function getErrors() : array
    {
        return $this->errors;
    }
}
