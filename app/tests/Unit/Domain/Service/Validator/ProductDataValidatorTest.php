<?php

declare(strict_types=1);

use App\Domain\Service\Validator\ProductDataValidator;

beforeEach(function () {
    $this->validator = new ProductDataValidator();
});

it('validates a valid product data', function () {
    $data = [
        'Product Code' => 'P001',
        'Product Name' => 'Valid Product',
        'Product Description' => 'This is a valid product description.',
        'Discontinued' => '',
    ];

    $errors = $this->validator->validate($data);

    expect($errors)->toBeEmpty();
});

it('returns error for missing product code', function () {
    $data = [
        'Product Code' => '',
        'Product Name' => 'Valid Product',
        'Product Description' => 'This is a valid product description.',
        'Discontinued' => '',
    ];

    $errors = $this->validator->validate($data);

    expect($errors)->toContain('The "Product Code" field is required.');
});

it('returns error for duplicate product code', function () {
    $productDataOne = [
        'Product Code' => 'P001',
        'Product Name' => 'First Product',
        'Product Description' => 'First product description.',
        'Discontinued' => '',
    ];

    $productDataTwo = [
        'Product Code' => 'P001',
        'Product Name' => 'Duplicate Product',
        'Product Description' => 'Duplicate product description.',
        'Discontinued' => '',
    ];

    $this->validator->validate($productDataOne);
    $errors = $this->validator->validate($productDataTwo);

    expect($errors)->toContain('The "Product Code" field is already in use.');
});

it('returns error for missing product name', function () {
    $data = [
        'Product Code' => 'P001',
        'Product Name' => '',
        'Product Description' => 'Valid description.',
        'Discontinued' => '',
    ];

    $errors = $this->validator->validate($data);

    expect($errors)->toContain('The "Product Name" field is required.');
});

it('returns error for missing product description', function () {
    $data = [
        'Product Code' => 'P001',
        'Product Name' => 'Valid Product',
        'Product Description' => '',
        'Discontinued' => '',
    ];

    $errors = $this->validator->validate($data);

    expect($errors)->toContain('The "Product Description" field is required.');
});

it('returns error for invalid discontinued value', function () {
    $data = [
        'Product Code' => 'P001',
        'Product Name' => 'Valid Product',
        'Product Description' => 'Valid description.',
        'Discontinued' => 'invalid',
    ];

    $errors = $this->validator->validate($data);

    expect($errors)->toContain('The "Discontinued" field must be "yes" or empty.');
});
