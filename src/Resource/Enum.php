<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Resource;

abstract class Enum
{
    protected string $value;

    final private function __construct($value)
    {
        if (!$this->isValid($value)) {
            throw new \InvalidArgumentException("Value '$value' is not part of the enum " . static::class);
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function fromString(string $value)
    {
        return new static($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function isValid(string $value): bool
    {
        return \in_array($value, static::toArray(), true);
    }

    public static function toArray(): array
    {
        $reflection = new \ReflectionClass(static::class);

        return $reflection->getConstants();
    }
}
