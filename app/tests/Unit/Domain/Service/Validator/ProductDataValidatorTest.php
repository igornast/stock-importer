<?php

declare(strict_types=1);

use App\Domain\Service\Validator\ProductDataValidator;

beforeEach(function () {
    $this->validator = new ProductDataValidator();
});

it('returns error for duplicate product code', function () {
    $productDataOne = [
        'Product Code' => 'P001',
        'Product Name' => 'First Product',
        'Product Description' => 'First product description.',
        'Discontinued' => '',
        'Stock' => '0',
        'Cost in GBP' => '10.00',
    ];

    $productDataTwo = [
        'Product Code' => 'P001',
        'Product Name' => 'Duplicate Product',
        'Product Description' => 'Duplicate product description.',
        'Discontinued' => '',
        'Stock' => '0',
        'Cost in GBP' => '10.00',
    ];

    $this->validator->validate($productDataOne);
    $errors = $this->validator->validate($productDataTwo);

    expect($errors)->toContain('The "Product Code" field is already in use.');
});

dataset('product_data', [
    'valid product data' => [
        [
            'Product Code' => 'P001',
            'Product Name' => 'Valid Product',
            'Product Description' => 'This is a valid product description.',
            'Discontinued' => '',
            'Stock' => '10',
            'Cost in GBP' => '50.00',
        ],
        [],
    ],
    'missing product code' => [
        [
            'Product Code' => '',
            'Product Name' => 'Valid Product',
            'Product Description' => 'This is a valid product description.',
            'Discontinued' => '',
            'Stock' => '0',
            'Cost in GBP' => '10.00',
        ],
        ['The "Product Code" field is required.'],
    ],
    'missing product name' => [
        [
            'Product Code' => 'P001',
            'Product Name' => '',
            'Product Description' => 'Valid description.',
            'Discontinued' => '',
            'Stock' => '0',
            'Cost in GBP' => '10.00',
        ],
        ['The "Product Name" field is required.'],
    ],
    'missing product description' => [
        [

            'Product Code' => 'P001',
            'Product Name' => 'Valid Product',
            'Product Description' => '',
            'Discontinued' => '',
            'Stock' => '0',
            'Cost in GBP' => '10.00',
        ],
        ['The "Product Description" field is required.'],
    ],
    'invalid discontinued value' => [
        [
            'Product Code' => 'P001',
            'Product Name' => 'Valid Product',
            'Product Description' => 'Valid description.',
            'Discontinued' => 'invalid',
            'Stock' => '0',
            'Cost in GBP' => '10.00',
        ],
        ['The "Discontinued" field must be "yes" or empty, "invalid" given.'],
    ],
    'missing stock field' => [
        [

            'Product Code' => 'P001',
            'Product Name' => 'Valid Product',
            'Product Description' => 'Valid description.',
            'Discontinued' => '',
            'Stock' => '',
            'Cost in GBP' => '10.00',
        ],
        ['The "Stock" field must be a valid number, "" given.'],
    ],
    'missing cost field' => [
        [


            'Product Code' => 'P001',
            'Product Name' => 'Valid Product',
            'Product Description' => 'Valid description.',
            'Discontinued' => '',
            'Stock' => '10',
            'Cost in GBP' => '',
        ],
        ['The "Cost" field must be a number, "" given.'],
    ],
    'non-numeric stock field' => [
        [
            'Product Code' => 'P001',
            'Product Name' => 'Valid Product',
            'Product Description' => 'Valid description.',
            'Discontinued' => '',
            'Stock' => 'abc',
            'Cost in GBP' => '10.00',
        ],
        ['The "Stock" field must be a valid number, "abc" given.'],
    ],
    'non-numeric cost field' => [
        [

            'Product Code' => 'P001',
            'Product Name' => 'Valid Product',
            'Product Description' => 'Valid description.',
            'Discontinued' => '',
            'Stock' => '10',
            'Cost in GBP' => 'invalid',
        ],
        ['The "Cost" field must be a number, "invalid" given.'],
    ],
    'stock below 10 and cost below 5' => [
        [
            'Product Code' => 'P001',
            'Product Name' => 'Valid Product',
            'Product Description' => 'Valid description.',
            'Discontinued' => '',
            'Stock' => '5',
            'Cost in GBP' => '4.99',
        ],
        ['An item with cost under 5 GBP and less than 10 items is not allowed.'],
    ],
    'cost over 1000' => [
        [
            'Product Code' => 'P001',
            'Product Name' => 'Valid Product',
            'Product Description' => 'Valid description.',
            'Discontinued' => '',
            'Stock' => '10',
            'Cost in GBP' => '1000.01',
        ],
        ['An item  with cost over 1000 GBP is not allowed.'],
    ],
]);


it('validates product data', function ($data, $expectedErrors) {
    $errors = $this->validator->validate($data);

    expect($errors)->toEqual($expectedErrors);
})->with('product_data');
