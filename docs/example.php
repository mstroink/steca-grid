<?php
require_once 'vendor/autoload.php';

$Inverter = new \mstroink\StecaGrid\Inverter([
    'host' => '192.168.1.164',
]);

echo "<pre>";
print_r($Inverter->getDaily());
print_r($Inverter->getMeasurements());
