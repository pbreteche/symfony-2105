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

        if ($response->getStatusCode() >= 400) {
            throw new \Exception('Erreur sur l\'API');
        }

        return json_decode($response->getContent());
    }

    public function search(int $ibuMin = 0, int $ibuMax = 100)
    {
        $path = '/beers?ibu_gt='.$ibuMin.'&ibu_lt='.$ibuMax;
        $response = $this->client->request('GET', $this->endpoint.$path);

        return json_decode($response->getContent());
    }
}