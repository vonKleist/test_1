<?php

use App\CountryResolver;
use App\FileReader;
use App\gateways\RateGateway;
use App\RateCalculator;

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/app/RateCalculator.php';

$app = new RateCalculator(
    New FileReader(),
    New RateGateway(),
    New CountryResolver(),
);
$rates = $app->calculate($argv[1]);

echo implode(",\n", $rates);