<?php

namespace App\Interfaces;

interface FileRowInterface
{
    public function getBin(): string;
    public function getAmount(): string;
    public function getCurrency(): string;
}