<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Resource\Measurements;

use InvalidArgumentException;
use MStroink\StecaGrid\Resource\InverterResponse;

final class Measurements implements InverterResponse
{
    private Measurement $dcVoltage;
    private ?Measurement $dcCurrent;
    private ?Measurement $dcPower;
    private ?Measurement $acVoltage;
    private ?Measurement $acCurrent;
    private ?Measurement $acPower;
    private ?Measurement $acFrequency;
    private ?Measurement $temp;

    private function __construct(
        Measurement $dcVoltage,
        ?Measurement $dcCurrent = null,
        ?Measurement $dcPower = null,
        ?Measurement $acVoltage = null,
        ?Measurement $acCurrent = null,
        ?Measurement $acPower = null,
        ?Measurement $acFrequency = null,
        ?Measurement $temp = null
    ) {
        $this->dcVoltage = $dcVoltage;
        $this->dcCurrent = $dcCurrent;
        $this->dcPower = $dcPower;
        $this->acVoltage = $acVoltage;
        $this->acCurrent = $acCurrent;
        $this->acPower = $acPower;
        $this->acFrequency = $acFrequency;
        $this->temp = $temp;
    }

    public static function create(array $data): self
    {
        $measurements = [];

        foreach ($data as $type => $measurement) {
            $measurements[$type] = Measurement::create($measurement);
        }

        $dcVoltage = $measurements['dc_voltage'] ?? null;

        if (!$dcVoltage) {
            throw new \InvalidArgumentException('Missing required key `dc_voltage` from $data array');
        }

        return new self(
            $dcVoltage,
            $measurements['dc_current'] ?? null,
            $measurements['dc_power'] ?? null,
            $measurements['ac_voltage'] ?? null,
            $measurements['ac_current'] ?? null,
            $measurements['ac_power'] ?? null,
            $measurements['ac_frequency'] ?? null,
            $measurements['temp'] ?? null
        );
    }

    public function getDcVoltage(): Measurement
    {
        return $this->dcVoltage;
    }

    public function getDcCurrent(): ?Measurement
    {
        return $this->dcCurrent;
    }

    public function getDcPower(): ?Measurement
    {
        return $this->dcPower;
    }

    public function getAcVoltage(): ?Measurement
    {
        return $this->acVoltage;
    }

    public function getAcCurrent(): ?Measurement
    {
        return $this->acCurrent;
    }

    public function getAcPower(): ?Measurement
    {
        return $this->acPower;
    }

    public function getAcFrequency(): ?Measurement
    {
        return $this->acFrequency;
    }

    public function getTemp(): ?Measurement
    {
        return $this->temp;
    }

    public function toArray(): array
    {
        $measurements = array_filter([
            'dc_voltage' => $this->dcVoltage,
            'dc_current' => $this->dcCurrent,
            'dc_power' => $this->dcPower,
            'ac_voltage' => $this->acVoltage,
            'ac_current' => $this->acCurrent,
            'ac_power' => $this->acPower,
            'ac_frequency' => $this->acFrequency,
            'temp' => $this->temp,
        ]);

        return array_map(fn (Measurement $m) => $m->toArray(), $measurements);
    }

    public function toList(): array
    {
        $measurements = $this->toArray();
        $keys = array_keys($measurements);
        $values = array_column($measurements, 'value');

        return array_combine($keys, $values);
    }
}
