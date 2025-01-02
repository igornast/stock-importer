<?php

declare(strict_types=1);

use App\Infrastructure\Doctrine\Entity\ProductData;
use App\Infrastructure\Doctrine\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Tests\Fixture\ProductDataFixture;
use Tests\Fixture\ProductDTOFixture;

beforeEach(function () {
    $this->entityManager = mock(EntityManagerInterface::class);
    $this->repository = mock(EntityRepository::class);
    $this->registry = mock(ManagerRegistry::class);

    $this->registry
        ->shouldReceive('getManagerForClass')
        ->once()
        ->with(ProductData::class)
        ->andReturn($this->entityManager);

    $this->entityManager
        ->shouldReceive('getRepository')
        ->andReturn($this->repository);

    $this->entityManager
        ->shouldReceive('getClassMetadata')
        ->andReturn(new ClassMetadata(ProductData::class));

    $this->productRepository = new ProductRepository($this->registry);
});

it('persists a new product data entity', function () {
    $productDTO = ProductDTOFixture::create();

    $this->entityManager
        ->shouldReceive('getUnitOfWork->getEntityPersister->load')
        ->once()
        ->andReturn(null);

    $this->entityManager->shouldReceive('persist')
        ->withArgs(function (ProductData $productData) use ($productDTO) {
            return $productData->code === $productDTO->code
                && $productData->name === $productDTO->name
                && $productData->description === $productDTO->description
                && $productData->discontinuedAt === $productDTO->discontinuedAt
            ;
        })
        ->once();

    $this->productRepository->upsert($productDTO);
});

it('persists an updated product data entity', function () {
    $productDTO = ProductDTOFixture::create();
    $productData = ProductDataFixture::create(['discontinuedAt' => null]);

    $this->entityManager
        ->shouldReceive('getUnitOfWork->getEntityPersister->load')
        ->once()
        ->andReturn($productData);

    $this->entityManager->shouldReceive('persist')
        ->withArgs(function (ProductData $productData) use ($productDTO) {
            return $productData->name === $productDTO->name
                && $productData->description === $productDTO->description
                && $productData->discontinuedAt === $productDTO->discontinuedAt
            ;
        })
        ->once();

    $this->productRepository->upsert($productDTO);

});

it('calls flush', function () {
    $this->entityManager->shouldReceive('flush')->once();

    $this->productRepository->flush();
});
