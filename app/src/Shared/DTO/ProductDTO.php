<?php

declare(strict_types=1);

namespace App\Shared\DTO;

use Money\Money;

readonly class ProductDTO
{
    public function __construct(
        public ?string $name,
        public ?string $description,
        public ?string $code,
        public ?int $stock = null,
        public ?Money $price = null,
        public ?\DateTimeImmutable $discontinuedAt = null,
    ) {
    }

    /**
     * @param array<string, string> $data
     */
    public static function createFromArray(array $data): self
    {
        $cost = (int) round((float) $data['Cost in GBP'] * 100);
        $price = Money::GBP($cost);

        return new self(
            name: $data['Product Name'] ?? null,
            description: $data['Product Description'] ?? null,
            code: $data['Product Code'] ?? null,
            stock: (int) $data['Stock'],
            price: $price,
            discontinuedAt: ($data['Discontinued'] ?? null) === 'yes' ? new \DateTimeImmutable() : null,
        );
    }
}
