<?php

namespace App;

use App\exceptions\GetCountryException;
use App\exceptions\GetRateException;
use App\Interfaces\FileReaderInterface;
use App\Interfaces\RateGatewayInterface;

class RateCalculator
{
    //Would be better to place consts to env-variables if they could be changed
    private const BASE_CURRENCY = 'EUR';
    private const EU_COMMISSION = '0.01';
    private const NON_EU_COMMISSION = '0.02';
    private const SCALE = 2;
    public function __construct(
        private FileReaderInterface $fileReader,
        private RateGatewayInterface $rateGateway,
        private CountryResolver $countryResolver,
    ) {
    }

    public function calculate(string $fileName): array
    {
        $rates = [];

        foreach ($this->fileReader->readRow($fileName) as $row) {
            try {
                $isEu = $this->countryResolver->isEu($row->getBin());
                $rate = $this->rateGateway->getRate($row->getCurrency());

                $amount = $row->getAmount();
                if ($row->getCurrency() !== self::BASE_CURRENCY || bccomp($rate, 0) === 1) {
                    $amount = bcdiv($row->getAmount(), $rate);
                }

                $amountWithCommission = bcmul(
                    $amount,
                    $isEu ? self::EU_COMMISSION : self::NON_EU_COMMISSION,
                    self::SCALE
                );

                $rates[] = $amountWithCommission;
            } catch (GetCountryException|GetRateException $exception) {
                $rates[] = $exception->getMessage();
            }
        }

        return $rates;
    }
}