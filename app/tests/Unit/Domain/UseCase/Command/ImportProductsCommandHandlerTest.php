<?php

declare(strict_types=1);

use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\Service\Validator\ProductDataValidator;
use App\Domain\UseCase\Command\ImportProductsCommandHandler;
use App\Shared\DTO\ImportResultDTO;
use App\Shared\DTO\ProductDTO;

beforeEach(function () {
    $this->productRepository = mock(ProductRepositoryInterface::class);
    $this->productValidator = mock(ProductDataValidator::class);

    $this->handler = new ImportProductsCommandHandler(
        $this->productRepository,
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

    $this->productRepository
        ->shouldReceive('upsert')
        ->twice()
        ->withArgs(fn (ProductDTO $dto) => in_array($dto->code, ['P001', 'P002']))
        ->andReturn();

    $this->productRepository
        ->shouldReceive('flush')
        ->once()
        ->andReturn();

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

    $this->productRepository
        ->shouldNotReceive('upsert');

    $this->productRepository
        ->shouldReceive('flush')
        ->once()
        ->andReturn();

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

    $this->productRepository
        ->shouldReceive('upsert')
        ->twice()
        ->withArgs(fn (ProductDTO $dto) => in_array($dto->code, ['P001', 'P002']))
        ->andReturn();

    $this->productRepository
        ->shouldReceive('flush')
        ->once()
        ->andReturn();

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
