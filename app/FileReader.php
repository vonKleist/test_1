<?php

namespace App;

use App\factories\FileRowFactory;
use App\Interfaces\FileReaderInterface;

class FileReader implements FileReaderInterface
{
    private FileRowFactory $rowFactory;
    public function __construct()
    {
        $this->rowFactory = new FileRowFactory();
    }

    public function readRow(string $fileName): iterable
    {
        $file = fopen($fileName, 'r');

        while ($row = fgets($file)) {
            yield $this->rowFactory->createFromJson($row);
        }

        fclose($file);
    }
}