<?php

namespace App\Tests\UseCases\Products;

use App\Entity\Offer;
use App\Tests\BaseDatabaseTestCase;
use App\UseCases\Products\GetProductForOfferUseCase;

class GetProductForOfferUseCaseTest extends BaseDatabaseTestCase
{
    private const TEST_BRAND_NAME = 'test_brand';
    private const TEST_VENDOR_CODE = 'vendor_code';

    public function testGetProducts()
    {
        // Create a new Product and a new Brand
        $firstName = 'Средство Чистящее Domestos Professional Дезинфицирующее для Клининга 1500 мл';

        $firstOffer = (new Offer())
            ->setBrand(self::TEST_BRAND_NAME)
            ->setName($firstName)
            ->setPrice(100)
            ->setVendorCode(self::TEST_VENDOR_CODE)
            ->setVendorId(112321);

        /** @var GetProductForOfferUseCase $sut */
        $sut = $this->container->get(GetProductForOfferUseCase::class);

        $firstOfferProducts = $sut->linkProducts($firstOffer);

        $this->assertCount(1, $firstOfferProducts);

        $product = $firstOfferProducts[0];

        $this->assertEquals($firstName, $product->getName());
        $this->assertEquals(self::TEST_BRAND_NAME, $product->getBrand()->getName());


        // Now create the second offer and link it to the same product
        $secondName = 'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл';

        $secondOffer = (new Offer())
            ->setBrand(self::TEST_BRAND_NAME)
            ->setName($secondName)
            ->setPrice(80)
            ->setVendorCode(self::TEST_VENDOR_CODE)
            ->setVendorId(112323);

        $secondOfferProducts = $sut->linkProducts($secondOffer);

        $this->assertCount(1, $secondOfferProducts);

        $secondOfferProduct = $secondOfferProducts[0];

        $this->assertEquals($product, $secondOfferProduct);


        // Now create the third offer that does not match the current products
        $thirdName = 'Универсальное чистящее средство гель для унитаза и уборки';

        $thirdOffer = (new Offer())
            ->setBrand(self::TEST_BRAND_NAME)
            ->setName($thirdName)
            ->setPrice(112)
            ->setVendorCode(self::TEST_VENDOR_CODE)
            ->setVendorId(112325);

        $thirdOfferProducts = $sut->linkProducts($thirdOffer);

        $this->assertCount(1, $thirdOfferProducts);

        $thirdOfferProduct = $secondOfferProducts[0];

        $this->assertEquals($product, $thirdOfferProduct);
    }
}
