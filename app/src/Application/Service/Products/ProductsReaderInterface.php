<?php

declare(strict_types=1);

namespace App\Application\Service\Products;

interface ProductsReaderInterface
{
    public function read(string $filePath): \Generator;
}
