<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Exception;

class HttpServerException extends InverterException
{
    public static function networkError(\Throwable $previous): self
    {
        return new self('The Steca Grid Inverter is currently unreachable.', 0, $previous);
    }

    public static function serverError(int $httpStatus = 500): self
    {
        return new self('An unexpected error occurred', $httpStatus);
    }

    public static function unknownHttpResponseCode(int $code): self
    {
        return new self(sprintf('Unknown HTTP response code ("%d") received from the Inverter', $code));
    }
}
