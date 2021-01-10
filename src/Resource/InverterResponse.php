<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Resource;

interface InverterResponse
{
    public static function create(array $data);
    public function toArray(): array;
}
