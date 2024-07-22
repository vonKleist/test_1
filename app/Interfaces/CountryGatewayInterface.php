<?php

namespace App\Interfaces;

interface CountryGatewayInterface
{
    public function getCountryCodeByBin(string $bin): string;
}