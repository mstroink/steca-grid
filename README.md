# StecaGrid
Dislaimer: i don't own a StecaGrid Inverter

# Install

`composer require mstroink/steca-grid`

run tests

`vendor/bin/phpunit`

# Usage

```php
use MStroink\StecaGrid\Inverter;

require_once 'vendor/autoload.php';

$inverter = Inverter::create('192.168.1.164');

echo "<pre>";
print_r($inverter->getDaily());
print_r($inverter->getMeasurements());
```