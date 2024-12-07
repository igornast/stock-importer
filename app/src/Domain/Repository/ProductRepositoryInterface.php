<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Shared\DTO\ProductDTO;

interface ProductRepositoryInterface
{
    public function upsert(ProductDTO $productDTO): void;

    public function flush(): void;
}
