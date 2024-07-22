<?php

namespace App\dto;

use App\Interfaces\FileRowInterface;

class FileRow implements FileRowInterface
{
    public function __construct(
        private readonly string $bin,
        private readonly string $amount,
        private readonly string $currency
    ) {
    }

    public function getBin(): string
    {
        return $this->bin;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}