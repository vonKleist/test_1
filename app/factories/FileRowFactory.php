<?php

namespace App\factories;

use App\dto\FileRow;
use App\Interfaces\FileRowInterface;

class FileRowFactory
{
    public static function createFromJson(string $row): FileRowInterface
    {
        return new FileRow(...json_decode($row, true));
    }
}