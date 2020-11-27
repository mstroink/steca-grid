<?php

use MStroink\StecaGrid\Inverter;

require_once 'vendor/autoload.php';

$inverter = Inverter::create('192.168.1.164');

echo "<pre>";
print_r($inverter->getDaily());
print_r($inverter->getMeasurements());
