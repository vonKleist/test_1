<?php

namespace App\gateways;

use App\exceptions\GetRateException;
use App\Interfaces\RateGatewayInterface;
use GuzzleHttp\Client;
use Throwable;

class RateGateway implements RateGatewayInterface
{
    private string $url;
    private Client $client;

    public function __construct()
    {
        $this->url = getenv('https://api.exchangeratesapi.io/latest');
        $this->client = new Client();
    }

    //Would be good to add cache for rates
    public function getRate(string $currency): string
    {
        try {
            $rateResults = $this->client->request('GET', $this->url);

            $decodedRateResults = json_decode($rateResults->getBody(), true);

            if (isset($decodedRateResults['rate'][$currency])) {
                return $decodedRateResults['rate'][$currency];
            }
        } catch (Throwable) {}

        throw new GetRateException('Cant fetch rate for currency ' . $currency);
    }
}