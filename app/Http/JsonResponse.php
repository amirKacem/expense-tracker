<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\ResponseInterface;

class JsonResponse
{
    public function __construct(private ResponseInterface $response)
    {

    }
    public function json(mixed $data): ResponseInterface
    {
        $this->response = $this->response->withHeader('Content-Type', 'application/json');
        $this->response->write(json_encode($data));

        return $this->response;
    }

}
