<?php

declare(strict_types=1);

namespace MStroink\StecaGrid\Exception;

use Psr\Http\Message\ResponseInterface;

class HttpClientException extends InverterException
{
    private ResponseInterface $response;

    public function __construct(string $message, int $code, ResponseInterface $response)
    {
        parent::__construct($message, $code);

        $this->response = $response;
    }

    public static function notFound(ResponseInterface $response): self
    {
        return new self('The endpoint you have tried to access does not exist.', 404, $response);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
