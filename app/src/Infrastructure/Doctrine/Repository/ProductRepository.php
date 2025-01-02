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
        $productData = $this->findOneBy(['code' => $productDTO->code]);

        if ($productData instanceof ProductData) {
            $productData
                ->setName($productDTO->name)
                ->setDescription($productDTO->description)
                ->setPrice($productDTO->price)
                ->setStock($productDTO->stock);

            if (null === $productData->discontinuedAt && null !== $productDTO->discontinuedAt) {
                $productData->setDiscontinuedAt($productDTO->discontinuedAt);
            }

            $this->getEntityManager()->persist($productData);

            return;
        }

        $productData = new ProductData(
            name: $productDTO->name,
            description: $productDTO->description,
            code: $productDTO->code,
            stock: $productDTO->stock,
            price: $productDTO->price,
            createdAt: new \DateTimeImmutable(),
            discontinuedAt: $productDTO->discontinuedAt,
        );

        $this->getEntityManager()->persist($productData);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
