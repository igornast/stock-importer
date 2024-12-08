<?php

declare(strict_types=1);

namespace App\Domain\UseCase\Command;

use App\Domain\Service\Validator\ProductDataValidator;
use App\Shared\DTO\ImportResultDTO;

class DryRunImportProductsCommandHandler
{
    public function __construct(
        private readonly ProductDataValidator $productValidator,
    ) {
    }

    /**
     * @param array<int, array<string, string>> $products
     */
    public function handle(array $products): ImportResultDTO
    {
        $processed = 0;
        $invalid = [];

        foreach ($products as $row => $productData) {
            $errors = $this->productValidator->validate($productData);

            if (count($errors) > 0) {
                $invalid[$row + 1] = $errors;
                ++$processed;
                continue;
            }

            ++$processed;
        }

        return new ImportResultDTO($processed, $processed - count($invalid), $invalid);
    }
}