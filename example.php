<?php
require_once 'vendor/autoload.php';

$Inverter = new \mstroink\StecaGrid\Inverter();

echo "<pre>";
print_r($Inverter->getDaily());
print_r($Inverter->getMeasurements());
