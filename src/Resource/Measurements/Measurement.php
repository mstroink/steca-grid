<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Resource\Measurements;

use MStroink\StecaGrid\Resource\InverterResponse;
use MStroink\StecaGrid\Resource\Measurements\MeasurementType;
use MStroink\StecaGrid\Resource\Measurements\MeasurementUnit;

final class Measurement implements InverterResponse
{
    private ?float $value;
    private MeasurementUnit $unit;
    private MeasurementType $type;

    private function __construct(?float $value, MeasurementUnit $unit, MeasurementType $type)
    {
        $this->value = $value;
        $this->unit = $unit;
        $this->type = $type;
    }

    public static function create(array $data): self
    {
        $unit = $data['unit'] ?? null;
        $type = $data['type'] ?? null;
        $value = $data['value'] ?? null;

        if (!$unit) {
            throw new \InvalidArgumentException('Missing key `unit` from $data array');
        }

        if (!$type) {
            throw new \InvalidArgumentException('Missing key `type` from $data array');
        }

        $type = MeasurementType::fromString($type);
        $unit = MeasurementUnit::fromString($unit);

        return new self($value, $unit, $type);
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function getUnit(): string
    {
        return $this->unit->getValue();
    }

    public function getType(): string
    {
        return $this->type->getValue();
    }

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'unit' => $this->getUnit(),
            'type' => $this->getType(),
        ];
    }

    public function __toString(): string
    {
        return "{$this->getValue()} {$this->getUnit()}";
    }
}
