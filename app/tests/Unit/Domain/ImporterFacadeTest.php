<?php

declare(strict_types=1);


use App\Domain\ImporterFacade;
use App\Domain\UseCase\Command\DryRunImportProductsCommandHandler;
use App\Domain\UseCase\Command\ImportProductsCommandHandler;
use App\Shared\DTO\ImportResultDTO;

beforeEach(function () {
    $this->importProductsCommand = mock(ImportProductsCommandHandler::class);
    $this->importDryRunProductsCommand = mock(DryRunImportProductsCommandHandler::class);

    $this->facade = new ImporterFacade($this->importProductsCommand, $this->importDryRunProductsCommand);
});

it('calls product import command', function () {
    $productData = [
        ['Product Code' => 'P001', 'Product Name' => 'TV', 'Product Description' => '32" TV', 'Discontinued' => ''],
        ['Product Code' => 'P002', 'Product Name' => '', 'Product Description' => 'Portable Radio', 'Discontinued' => 'yes'],

    ];

    $this->importProductsCommand
        ->shouldReceive('handle')
        ->once()
        ->with($productData)
        ->andReturn(new ImportResultDTO(2, 2, []));

    $result = $this->facade->importProducts($productData);

    expect($result)->toBeInstanceOf(ImportResultDTO::class)
        ->and($result->processedCount)->toBe(2)
        ->and($result->successfulCount)->toBe(2)
        ->and($result->failed)->toHaveCount(0);
});

it('calls dry-run product import command', function () {
    $productData = [
        ['Product Code' => 'P001', 'Product Name' => 'TV', 'Product Description' => '32" TV', 'Discontinued' => ''],
        ['Product Code' => 'P002', 'Product Name' => '', 'Product Description' => 'Portable Radio', 'Discontinued' => 'yes'],

    ];

    $this->importDryRunProductsCommand
        ->shouldReceive('handle')
        ->once()
        ->with($productData)
        ->andReturn(new ImportResultDTO(2, 2, []));

    $result = $this->facade->dryRunImportProducts($productData);

    expect($result)->toBeInstanceOf(ImportResultDTO::class)
        ->and($result->processedCount)->toBe(2)
        ->and($result->successfulCount)->toBe(2)
        ->and($result->failed)->toHaveCount(0);
});
