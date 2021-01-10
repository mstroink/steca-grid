# StecaGrid
Dislaimer: i don't own a StecaGrid Inverter

# Install

`composer require mstroink/steca-grid`

run tests

`vendor/bin/phpunit`

# Usage

### Create inverter client

```php
use MStroink\StecaGrid\Inverter;

require_once 'vendor/autoload.php';

$inverter = Inverter::create('192.168.178.10'); // host
```

### Measurements
```php
$measurements = $inverter->getMeasurements();

$measurements->getAcCurrent();
$measurements->getAcFrequency();
$measurements->getAcPower();
$measurements->getAcVoltage();
$measurements->getDcCurrent();
$measurements->getDcPower();
$measurements->getDcVoltage();
$measurements->getTemp();

// Measurement object
echo $measurements->getDcVoltage()->getValue(); // 123.123;
echo $measurements->getDcVoltage()->getUnit(); // V;
echo $measurements->getDcVoltage()->getType(); // DC_Voltage;

// As string
echo (string) $measurements->getDcVoltage(); // '123.123 V'

// As array
print_r($measurements->toArray()); // ['dc_voltage' => ['value' => 123.123, 'unit' => DC_Voltage, 'type' => 'V']]
print_r($measurements->toList()); // ['dc_voltage' => 123.123, 'ac_power' => 12.12]

```


### Daily
```php
$yield = $inverter->getYieldToday();
$yield->getTotal(); // 123.45;

//As string
echo (string) $yield; // "123.45 kWh"
```
# Tests

```sh
vendor/bin/phpunit
````