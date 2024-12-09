<?php

declare(strict_types=1);

namespace App\Domain\Service\Validator;

class ProductDataValidator
{
    /**
     * @param string[] $checkedCodes
     */
    public function __construct(
        private array $checkedCodes = [],
    ) {
    }

    /**
     * @param array<string, string> $data
     *
     * @return array<int, string>
     */
    public function validate(array $data): array
    {
        $errors = [];

        $errors = array_merge($errors, $this->validateProductCode($data['Product Code']));
        $errors = array_merge($errors, $this->validateProductName($data['Product Name']));
        $errors = array_merge($errors, $this->validateProductDescription($data['Product Description']));
        $errors = array_merge($errors, $this->validateDiscontinued($data['Discontinued']));
        $errors = array_merge($errors, $this->validateStockAndCost($data['Stock'], $data['Cost in GBP']));

        return $errors;
    }

    /**
     * @return array<int, string>
     */
    private function validateProductCode(?string $code): array
    {
        if (empty($code)) {
            return ['The "Product Code" field is required.'];
        }

        if (in_array($code, $this->checkedCodes, true)) {
            return  ['The "Product Code" field is already in use.'];
        }

        $this->checkedCodes[] = $code;

        return [];
    }

    /**
     * @return array<int, string>
     */
    private function validateProductName(?string $productName): array
    {
        return empty($productName)
            ? ['The "Product Name" field is required.']
            : [];
    }

    /**
     * @return array<int, string>
     */
    private function validateProductDescription(?string $productDescription): array
    {
        return empty($productDescription)
            ? ['The "Product Description" field is required.']
            : [];
    }

    /**
     * @return array<int, string>
     */
    private function validateDiscontinued(?string $discontinued): array
    {
        return '' !== $discontinued && 'yes' !== $discontinued
            ? [sprintf('The "Discontinued" field must be "yes" or empty, "%s" given.', $discontinued)]
            : [];
    }

    /**
     * @return array<int, string>
     */
    private function validateStockAndCost(?string $stock, ?string $cost): array
    {
        $errors = [];

        if (!ctype_digit($stock)) {
            $errors[] = sprintf('The "Stock" field must be a valid number, "%s" given.', $stock);
        }

        if (!is_numeric($cost)) {
            $errors[] = sprintf('The "Cost" field must be a number, "%s" given.', $cost);
        }

        if (count($errors) > 0) {
            return $errors;
        }

        $cost = (float) $cost;
        $stock = (int) $stock;

        if ($cost < 5 && $stock < 10) {
            $errors[] = 'An item with cost under 5 GBP and less than 10 items is not allowed.';
        }

        if ($cost > 1000) {
            $errors[] = 'An item  with cost over 1000 GBP is not allowed.';
        }

        return $errors;
    }
}
