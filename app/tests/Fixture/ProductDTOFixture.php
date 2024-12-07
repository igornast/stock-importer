<?php

declare(strict_types=1);

namespace Tests\Fixture;

use App\Shared\DTO\ProductDTO;

use function Pest\Faker\fake;

class ProductDTOFixture
{
    public static function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'code' => fake()->word(),
            'discontinuedAt' => new \DateTimeImmutable(),
        ];
    }

    public static function create(array $attributes = []): ProductDTO
    {
        return new ProductDTO(...array_merge(self::definition(), $attributes));
    }
}
