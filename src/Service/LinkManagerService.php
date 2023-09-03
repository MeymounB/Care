<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LinkManagerService
{
    private HttpService $httpService;
    private ParameterBagInterface $params;

    public function __construct(HttpService $httpService, ParameterBagInterface $params)
    {
        $this->httpService = $httpService;
        $this->params = $params;
    }

    public function createLink(array $data): string
    {
        $url = 'https://api.whereby.dev/v1/meetings/';
        $request = [
            'roomNamePrefix' => 'greencare-',
            'roomNamePattern' => 'uuid',
            'roomMode' => 'normal',
            'fields' => [],
            ...$data,
        ];

        $headers = [
            'Authorization' => 'Bearer '.$this->params->get('whereby_token'),
        ];

        $response = $this->httpService->post($url, $request, $headers);

        return $response['roomUrl'];
    }
}
