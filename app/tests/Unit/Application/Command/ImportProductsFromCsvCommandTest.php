<?php

declare(strict_types=1);

use App\Shared\DTO\ImportResultDTO;
use App\Shared\Facade\ImporterFacadeInterface;
use App\Application\Command\ImportProductsFromCsvCommand;
use App\Application\Service\Products\ProductsReaderInterface;
use Symfony\Component\Console\Tester\CommandTester;

beforeEach(function () {
    $this->productsReader = mock(ProductsReaderInterface::class);
    $this->importerFacade = mock(ImporterFacadeInterface::class);

    $this->command = new ImportProductsFromCsvCommand(
        $this->productsReader,
        $this->importerFacade,
        'products:import'
    );
});

it('imports products', function () {
    $filePath = '/path/test.csv';
    $this->productsReader
        ->shouldReceive('read')
        ->with($filePath)
        ->andReturnUsing(
            function () {
                $data = [
                    ['Product Code' => 'P001', 'Product Name' => 'TV', 'Product Description' => '32" TV', 'Discontinued' => ''],
                    ['Product Code' => 'P002', 'Product Name' => 'Radio', 'Product Description' => 'Portable Radio', 'Discontinued' => 'yes'],
                    ['Product Code' => 'P003', 'Product Name' => 'Computer', 'Product Description' => 'PC', 'Discontinued' => ''],
                ];

                foreach ($data as $item) {
                    yield $item;
                }
            }
        );

    $this->importerFacade
        ->shouldReceive('importProducts')
        ->andReturn(
            new ImportResultDTO(2, 2, []),
            new ImportResultDTO(1, 1, [])
        );

    $commandTester = new CommandTester($this->command);
    $commandTester->execute(['file' => $filePath, '--batch-size' => 2]);

    expect($commandTester->getStatusCode())->toBe(0)
        ->and($commandTester->getDisplay())
        ->toContain('Processed items: 3')
        ->toContain('Successfuly imported: 3')
        ->toContain('Failed to import: 0')
        ->not->toContain('Invalid Rows');
});


it('handles invalid products', function () {
    $filePath = '/path/test.csv';
    $this->productsReader
        ->shouldReceive('read')
        ->with($filePath)
        ->andReturnUsing(
            function () {
                $data = [
                    ['Product Code' => 'P001', 'Product Name' => 'TV', 'Product Description' => '32" TV', 'Discontinued' => ''],
                    ['Product Code' => '', 'Product Name' => 'Radio', 'Product Description' => 'Portable Radio', 'Discontinued' => 'yes'],
                ];

                foreach ($data as $item) {
                    yield $item;
                }
            }
        );

    $this->importerFacade
        ->shouldReceive('importProducts')
        ->andReturn(
            new ImportResultDTO(1, 1, []),
            new ImportResultDTO(1, 0, [1 => ['The "Product Code" field is required']]),
        );

    $commandTester = new CommandTester($this->command);
    $commandTester->execute(['file' => $filePath, '--batch-size' => 1]);

    expect($commandTester->getStatusCode())->toBe(0)
        ->and($commandTester->getDisplay())
        ->toContain('Processed items: 2')
        ->toContain('Successfuly imported: 1')
        ->toContain('Failed to import: 1')
        ->toContain('Invalid Rows');
});
