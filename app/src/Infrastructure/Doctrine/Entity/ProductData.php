<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Entity;

use Money\Money;

class ProductData
{
    public function __construct(
        public string $productName,
        public string $productDescription,
        public string $productCode,
        public ?int $productDataId = null,
        public ?int $productStock = null,
        public ?Money $productPrice = null,
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

    public function setProductStock(?int $productStock): ProductData
    {
        $this->productStock = $productStock;

        return $this;
    }

    public function setProductPrice(?Money $productPrice): ProductData
    {
        $this->productPrice = $productPrice;

        return $this;
    }
}
