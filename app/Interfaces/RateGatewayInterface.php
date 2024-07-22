<?php

namespace App\Interfaces;

interface RateGatewayInterface
{
    public function getRate(string $currency): string;
}