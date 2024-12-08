<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\UseCase\Command\DryRunImportProductsCommandHandler;
use App\Domain\UseCase\Command\ImportProductsCommandHandler;
use App\Shared\DTO\ImportResultDTO;
use App\Shared\Facade\ImporterFacadeInterface;

final class ImporterFacade implements ImporterFacadeInterface
{
    public function __construct(
        private readonly ImportProductsCommandHandler $importProductsCommand,
        private readonly DryRunImportProductsCommandHandler $runImportProductsCommand,
    ) {
    }

    /**
     * @param array<int, array<string, string>> $products
     */
    public function importProducts(array $products): ImportResultDTO
    {
        return $this->importProductsCommand->handle($products);
    }

    /**
     * @param array<int, array<string, string>> $products
     */
    public function dryRunImportProducts(array $products): ImportResultDTO
    {
        return $this->runImportProductsCommand->handle($products);
    }
}
