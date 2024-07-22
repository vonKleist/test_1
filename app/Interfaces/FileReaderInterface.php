<?php

namespace App\Interfaces;

interface FileReaderInterface
{
    public function readRow(string $fileName): iterable;
}