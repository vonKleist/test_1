<?php

namespace App;

use App\gateways\CountryGateway;
use App\Interfaces\CountryGatewayInterface;

class CountryResolver
{
    private const EU_COUNTRIES = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT',
        'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',
    ];

    private CountryGatewayInterface $countryGateway;

    public function __construct()
    {
        $this->countryGateway = new CountryGateway();
    }

    public function isEu(string $bin): bool
    {
        return $this->isEuByCode(
            $this->countryGateway->getCountryCodeByBin($bin)
        );
    }

    private function isEuByCode(string $alpha2Code): bool
    {
        return in_array($alpha2Code, self::EU_COUNTRIES, true);
    }
}