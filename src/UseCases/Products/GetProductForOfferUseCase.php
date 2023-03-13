<?php

declare(strict_types=1);

namespace App\UseCases\Products;

use App\Contracts\StringSimilarityProcessorInterface;
use App\Entity\Brand;
use App\Entity\Offer;
use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;

class GetProductForOfferUseCase
{
    public const SIMILARITY_THRESHOLD = 60;

    private ProductRepository $productRepository;

    private BrandRepository $brandRepository;

    private StringSimilarityProcessorInterface $similarityProcessor;

    public function __construct(
        ProductRepository $productRepository,
        BrandRepository $brandRepository,
        StringSimilarityProcessorInterface $similarityProcessor
    )
    {
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
        $this->similarityProcessor = $similarityProcessor;
    }


    /**
     * Finds suitable Products for the Offer. If no suitable Products found then one new Product will be created.
     *
     * @param Offer $offer
     * @return array<Product>
     */
    public function linkProducts(Offer $offer): array
    {
        $brand = $this->brandRepository->findOneBy(['name' => $offer->getBrand()]);

        if (empty($brand)) {
            $brand = (new Brand())->setName($offer->getBrand());

            $this->brandRepository->save($brand, true);

            return [$this->createProduct($offer, $brand)];
        }

        $products = $this->productRepository->findBy(['brand' => $brand->getId()]);

        $suitableProducts = [];

        foreach ($products as $product) {
            $similarityIndex = $this->similarityProcessor->getSimilarity($product->getName(), $offer->getName());

            if ($similarityIndex >= self::SIMILARITY_THRESHOLD) {
                $suitableProducts[] = $product;
            }
        }

        if (empty($suitableProducts)) {
            $suitableProducts[] = $this->createProduct($offer, $brand);
        }

        return $suitableProducts;
    }

    /**
     * @param Offer $offer
     * @param Brand $brand
     * @return Product
     */
    private function createProduct(Offer $offer, Brand $brand): Product
    {
        $product = new Product();

        $product->setName($offer->getName())->setBrand($brand);

        $this->productRepository->save($product, true);

        return $product;
    }
}
