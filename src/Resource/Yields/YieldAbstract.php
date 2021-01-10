<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Resource\Yields;

use MStroink\StecaGrid\Resource\InverterResponse;

abstract class YieldAbstract implements InverterResponse
{
    private float $total;

    final private function __construct(float $total)
    {
        $this->total = $total;
    }

    public static function create(array $data)
    {
        if (!isset($data['total'])) {
            throw new \InvalidArgumentException('Missing key `total` from $data array');
        }

        return new static((float) $data['total']);
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getUnit(): string
    {
        return "kWh";
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function __toString(): string
    {
        return "{$this->getTotal()} {$this->getUnit()}";
    }
}
