<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Infrastructure\Doctrine\Entity\ProductData;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Shared\DTO\ProductDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductData>
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductData::class);
    }

    public function upsert(ProductDTO $productDTO): void
    {
        $productData = $this->findOneBy(['productCode' => $productDTO->code]);

        if ($productData instanceof ProductData) {
            $productData
                ->setProductName($productDTO->name)
                ->setProductDescription($productDTO->description);

            if (null === $productData->dateDiscontinued && null !== $productDTO->discontinuedAt) {
                $productData->setDateDiscontinued($productDTO->discontinuedAt);
            }

            $this->getEntityManager()->persist($productData);

            return;
        }

        $productData = new ProductData(
            productName: $productDTO->name,
            productDescription: $productDTO->description,
            productCode: $productDTO->code,
            dateAdded: new \DateTimeImmutable(),
            dateDiscontinued: $productDTO->discontinuedAt,
        );

        $this->getEntityManager()->persist($productData);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
