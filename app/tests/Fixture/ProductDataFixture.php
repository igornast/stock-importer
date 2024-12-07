<?php

declare(strict_types=1);

namespace Tests\Fixture;

use App\Infrastructure\Doctrine\Entity\ProductData;

use function Pest\Faker\fake;

class ProductDataFixture
{
    public static function create(array $attributes = []): ProductData
    {
        return new ProductData(...array_merge(self::definition(), $attributes));
    }

    public static function definition(): array
    {
        return [
            'productName' => fake()->word(),
            'productDescription' => fake()->sentence(),
            'productCode' => fake()->word(),
            'productDataId' => fake()->numberBetween(1, 100),
            'dateAdded' => new \DateTimeImmutable(),
            'dateDiscontinued' => new \DateTimeImmutable(),
        ];
    }
}
