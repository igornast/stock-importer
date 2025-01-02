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
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'code' => fake()->word(),
            'id' => fake()->numberBetween(1, 100),
            'createdAt' => new \DateTimeImmutable(),
            'discontinuedAt' => new \DateTimeImmutable(),
        ];
    }
}
