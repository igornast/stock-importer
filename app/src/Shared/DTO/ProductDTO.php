<?php

declare(strict_types=1);

namespace App\Shared\DTO;

readonly class ProductDTO
{
    public function __construct(
        public ?string $name,
        public ?string $description,
        public ?string $code,
        public ?\DateTimeImmutable $discontinuedAt = null,
    ) {
    }

    /**
     * @param array<string, string> $data
     */
    public static function createFromArray(array $data): self
    {
        return new self(
            name: $data['Product Name'] ?? null,
            description: $data['Product Description'] ?? null,
            code: $data['Product Code'] ?? null,
            discontinuedAt: ($data['Discontinued'] ?? null) === 'yes' ? new \DateTimeImmutable() : null,
        );
    }
}
