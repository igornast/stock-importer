<?php

declare(strict_types=1);

use App\Domain\Service\Validator\ProductDataValidator;
use App\Domain\UseCase\Command\DryRunImportProductsCommandHandler;
use App\Shared\DTO\ImportResultDTO;

beforeEach(function () {
    $this->productValidator = mock(ProductDataValidator::class);

    $this->handler = new DryRunImportProductsCommandHandler(
        $this->productValidator,
    );
});

it('processes valid products', function () {
    $products = [
        ['Product Code' => 'P001', 'Product Name' => 'TV', 'Product Description' => '32" TV', 'Discontinued' => ''],
        ['Product Code' => 'P002', 'Product Name' => 'Radio', 'Product Description' => 'Portable Radio', 'Discontinued' => 'yes'],
    ];

    $this->productValidator
        ->shouldReceive('validate')
        ->andReturn([], []);

    $result = $this->handler->handle($products);

    expect($result)->toBeInstanceOf(ImportResultDTO::class)
        ->and($result->processedCount)->toBe(2)
        ->and($result->successfulCount)->toBe(2)
        ->and($result->failed)->toBe([]);
});

it('handles invalid products', function () {
    $products = [
        ['Product Code' => '', 'Product Name' => 'TV', 'Product Description' => '32" TV', 'Discontinued' => ''],
        ['Product Code' => 'P002', 'Product Name' => '', 'Product Description' => 'Portable Radio', 'Discontinued' => 'yes'],
    ];

    $this->productValidator
        ->shouldReceive('validate')
        ->andReturn(
            ['The "Product Code" field is required.'],
            ['The "Product Name" field is required.']
        );

    $result = $this->handler->handle($products);

    expect($result)->toBeInstanceOf(ImportResultDTO::class)
        ->and($result->processedCount)->toBe(2)
        ->and($result->successfulCount)->toBe(0)
        ->and($result->failed)->toHaveCount(2)
        ->and($result->failed)->toMatchArray([
            1 => ['The "Product Code" field is required.'],
            2 => ['The "Product Name" field is required.'],
        ]);
});

it('handles valid and invalid products', function () {
    $products = [
        ['Product Code' => 'P001', 'Product Name' => 'TV', 'Product Description' => '32" TV', 'Discontinued' => ''],
        ['Product Code' => 'P001', 'Product Name' => 'TV ', 'Product Description' => '32" TV', 'Discontinued' => ''],
        ['Product Code' => '', 'Product Name' => 'Liberty City Radio', 'Product Description' => 'Portable Radio', 'Discontinued' => 'yes'],
        ['Product Code' => 'P002', 'Product Name' => 'Radio', 'Product Description' => 'Portable Radio', 'Discontinued' => 'yes'],
    ];

    $this->productValidator
        ->shouldReceive('validate')
        ->andReturn(
            [],
            ['The "Product Code" field is already in use.'],
            ['The "Product Code" field is required.'],
            [],
        );

    $result = $this->handler->handle($products);

    expect($result)->toBeInstanceOf(ImportResultDTO::class)
        ->and($result->processedCount)->toBe(4)
        ->and($result->successfulCount)->toBe(2)
        ->and($result->failed)->toHaveCount(2)
        ->and($result->failed)->toMatchArray([
            2 => ['The "Product Code" field is already in use.'],
            3 => ['The "Product Code" field is required.'],
        ]);
});
