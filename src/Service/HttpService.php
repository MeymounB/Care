<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function get(string $url): array
    {
        $response = $this->client->request(
            'GET',
            $url
        );

        return $response->toArray();
    }

    public function post(string $url, array $data, array $headers): array
    {
        $response = $this->client->request(
            'POST',
            $url,
            [
                'body' => $data,
                'headers' => $headers,
            ]
        );

        return $response->toArray();
    }
}
