<?php

namespace App\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PunkApiClient
{

    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private $client;

    private $endpoint = 'https://api.punkapi.com/v2';

    public function __construct(
        HttpClientInterface $client
    ) {
        $this->client = $client;
    }

    public function random()
    {
        $response = $this->client->request('GET', $this->endpoint.'/beers/random');
        return json_decode($response->getContent());
    }
}