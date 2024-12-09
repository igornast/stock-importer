<?php

declare(strict_types=1);

namespace App\Application\Service\Products;

class CsvProductsReader implements ProductsReaderInterface
{
    public const array SUPPORTED_HEADERS = [
        'Product Code',
        'Product Name',
        'Product Description',
        'Discontinued',
        'Stock',
        'Cost in GBP',
    ];

    /**
     * @return \Generator<array<string, string>>
     */
    public function read(string $filePath): \Generator
    {
        $this->validateCsv($filePath);

        /** @var resource $resource */
        $resource = fopen($filePath, 'r');

        try {
            $headers = fgetcsv($resource);

            while (($row = fgetcsv($resource)) !== false) {
                yield array_combine($headers, $this->adjustRowDataIfNeeded($row, $headers));
            }

        } finally {
            fclose($resource);
        }

    }

    private function validateCsv(string $filePath): void
    {
        $errors = [];

        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \RuntimeException(sprintf('File "%s" does not exist or is not readable', $filePath));
        }

        if (!mb_check_encoding(file_get_contents($filePath), 'UTF-8')) {
            $errors[] = sprintf('File "%s" is not encoded in UTF-8.', $filePath);
        }

        $resource = fopen($filePath, 'r');
        $headers = fgetcsv($resource);

        $differences = array_diff($headers, self::SUPPORTED_HEADERS);
        if (!$headers || count($differences) > 0) {
            $errors[] = sprintf(
                'Unsupported header/s detected [%s]. Supported: [%s]',
                join(', ', $differences),
                join(', ', self::SUPPORTED_HEADERS),
            );
        }

        fclose($resource);

        if (count($errors) > 0) {
            throw new \RuntimeException('File errors detected: '.join(', ', $errors));
        }
    }

    /**
     * @param array<string, string> $row
     * @param array<int, string>    $headers
     *
     * @return array<int, string>
     */
    public function adjustRowDataIfNeeded(array $row, array $headers): array
    {
        $expectedCount = count($headers);
        $rowCount = count($row);

        if ($rowCount < $expectedCount) {
            $row = array_pad($row, count($headers), '');
        }

        if ($rowCount > $expectedCount) {
            $row = array_slice($row, 0, count($headers));
        }

        return $row;
    }
}
