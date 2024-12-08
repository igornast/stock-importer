<?php

declare(strict_types=1);

namespace App\Shared\Facade;

use App\Shared\DTO\ImportResultDTO;

interface ImporterFacadeInterface
{
    /**
     * @param array<int, array<string, string>> $products
     */
    public function importProducts(array $products): ImportResultDTO;

    /**
     * @param array<int, array<string, string>> $products
     */
    public function dryRunImportProducts(array $products): ImportResultDTO;
}
