<?php

declare(strict_types=1);

use App\Application\Service\Products\CsvProductsReader;

beforeEach(function () {
    $this->csvReader = new CsvProductsReader();
    $this->testFilePath = __DIR__.'/../../../../_sample/test_stock_file.csv';
    $this->testEmptyFilePath = __DIR__.'/../../../../_sample/test_empty_stock_file.csv';
    $this->testFilePathInvalidHeader = __DIR__.'/../../../../_sample/test_invalid_headers_stock_file.csv';
    $this->testFilePathInvalidEncoding = __DIR__.'/../../../../_sample/test_invalid_encoding_stock_file.csv';
});

afterAll(function () {
    unlink($this->testFilePath);
    unlink($this->testEmptyFilePath);
    unlink($this->testFilePathInvalidHeader);
});

it('reads a valid CSV file', function () {

    $generator = $this->csvReader->read($this->testFilePath);

    $rows = iterator_to_array($generator);

    expect($rows)->toHaveCount(2)
        ->and($rows[0])->toMatchArray([
            'Product Code' => 'P0001',
            'Product Name' => 'TV',
            'Product Description' => '32” Tv',
            'Discontinued' => '',
        ])
        ->and($rows[1])->toMatchArray([
            'Product Code' => 'P0002',
            'Product Name' => 'Cd Player',
            'Product Description' => 'Nice CD player',
            'Discontinued' => 'yes',
        ]);
});

it('handles an empty file', function () {
    $generator = $this->csvReader->read($this->testEmptyFilePath);
    $rows = iterator_to_array($generator);

    expect($rows)->toBeEmpty();
});


it('throws an exception for non-existent files', function () {
    $this->csvReader->read('./invalid_path.csv')->current();
})->throws(
    RuntimeException::class,
    'File "./invalid_path.csv" does not exist or is not readable'
);

it('throws an exception for incorrect headers', function () {
    $this->csvReader->read($this->testFilePathInvalidHeader)->current();
})->throws(
    RuntimeException::class,
    'Unsupported header/s detected [Invalid Header1, Invalid Header2, Invalid Header3]. Supported: [Product Code, Product Name, Product Description, Discontinued]'
);

it('throws an exception for invalid encoding', function () {
    $this->csvReader->read($this->testFilePathInvalidEncoding)->current();
})->throws(RuntimeException::class);
