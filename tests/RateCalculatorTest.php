<?php

use App\exceptions\GetRateException;
use App\exceptions\GetCountryException;
use PHPUnit\Framework\TestCase;
use App\CountryResolver;
use App\FileReader;
use App\gateways\RateGateway;
use App\RateCalculator;

class RateCalculatorTest extends TestCase
{
    /**
     * @dataProvider rateDataProvider
     */
    public function testRateCalculate($rows, $rate, $isEu, $result): void
    {
        $fileReaderMock = $this->createMock(FileReader::class);
        $fileReaderMock->expects($this->atLeastOnce())
            ->method('readRow')
            ->willReturn(
                array_map(
                    function ($row) {
                        return $this->createRowDto($row);
                    },
                    $rows
                ),
            );

        $rateGatewayMock = $this->createMock(RateGateway::class);

        if ($rate === -1) {
            $rateGatewayMock->expects($this->any())
                ->method('getRate')
                ->willThrowException(new GetRateException($result));
        } else {
            $rateGatewayMock->expects($this->any())
                ->method('getRate')
                ->willReturn($rate);
        }

        $countryResolverMock = $this->createMock(CountryResolver::class);

        if ($isEu === -1) {
            $countryResolverMock->expects($this->any())
                ->method('isEu')
                ->willThrowException(new GetCountryException($result));
        } else {
            $countryResolverMock->expects($this->any())
                ->method('isEu')
                ->willReturn($isEu);
        }


        $app = new RateCalculator(
            $fileReaderMock,
            $rateGatewayMock,
            $countryResolverMock,
        );
        $rates = $app->calculate('test');

        $this->assertSame($rates[0], $result);
    }

    public static function rateDataProvider(): array
    {
        return [
            'Success_EU' => [
                'rows' => [
                    '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
                ],
                'rate' => '2',
                'isEu' => true,
                'result' => '0.50',
            ],
            'Success_non_EU' => [
                'rows' => [
                    '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
                ],
                'rate' => '2',
                'isEu' => false,
                'result' => '1.00',
            ],
            'Country_error' => [
                'rows' => [
                    '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
                ],
                'rate' => '2',
                'isEu' => -1,
                'result' => 'Cant fetch country for bin 45717360',
            ],
            'Rate_error' => [
                'rows' => [
                    '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
                ],
                'rate' => -1,
                'isEu' => true,
                'result' => 'Cant fetch rate for currency EUR',
            ],
        ];
    }

    private function createRowDto($string)
    {
        return (new \App\factories\FileRowFactory())->createFromJson($string);
    }
}