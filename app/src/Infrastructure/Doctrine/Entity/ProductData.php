<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Entity;

class ProductData
{
    public function __construct(
        public string $productName,
        public string $productDescription,
        public string $productCode,
        public ?int $productDataId = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?\DateTimeImmutable $dateDiscontinued = null,
    ) {
    }

    public function setProductName(string $productName): ProductData
    {
        $this->productName = $productName;

        return $this;
    }

    public function setProductDescription(string $productDescription): ProductData
    {
        $this->productDescription = $productDescription;

        return $this;
    }

    public function setDateDiscontinued(?\DateTimeImmutable $dateDiscontinued): ProductData
    {
        $this->dateDiscontinued = $dateDiscontinued;

        return $this;
    }
}
