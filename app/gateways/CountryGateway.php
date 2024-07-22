<?php

namespace App\gateways;

use App\exceptions\GetCountryException;
use App\Interfaces\CountryGatewayInterface;
use GuzzleHttp\Client;
use Throwable;

class CountryGateway implements CountryGatewayInterface
{
    private string $url;
    private Client $client;

    public function __construct()
    {
        $this->url = getenv('BIN_URL');
        $this->client = new Client();
    }

    public function getCountryCodeByBin(string $bin): string
    {
        try {
            $binResults = $this->client->request('GET', $this->url . $bin);

            $decodedBinResults = json_decode($binResults->getBody(), true);

            if (isset($decodedBinResults['country']['alpha2'])) {
                return $decodedBinResults['country']['alpha2'];
            }
        } catch (Throwable) {}

        throw new GetCountryException('Cant fetch country for bin ' . $bin);
    }
}