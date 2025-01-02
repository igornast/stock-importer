<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Entity;

use Money\Money;

class ProductData
{
    public function __construct(
        public string $name,
        public string $description,
        public string $code,
        public ?int $id = null,
        public ?int $stock = null,
        public ?Money $price = null,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $discontinuedAt = null,
    ) {
    }

    public function setName(string $name): ProductData
    {
        $this->name = $name;

        return $this;
    }

    public function setDescription(string $description): ProductData
    {
        $this->description = $description;

        return $this;
    }

    public function setDiscontinuedAt(?\DateTimeImmutable $discontinuedAt): ProductData
    {
        $this->discontinuedAt = $discontinuedAt;

        return $this;
    }

    public function setStock(?int $stock): ProductData
    {
        $this->stock = $stock;

        return $this;
    }

    public function setPrice(?Money $price): ProductData
    {
        $this->price = $price;

        return $this;
    }
}
