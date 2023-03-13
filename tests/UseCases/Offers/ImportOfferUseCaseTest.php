<?php

namespace App\Tests\UseCases\Offers;

use App\DataObject\ImportResult;
use App\Entity\Offer;
use App\Repository\OfferRepository;
use App\Repository\PriceChangeRepository;
use App\Tests\BaseDatabaseTestCase;
use App\UseCases\Offers\ImportOfferUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ImportOfferUseCaseTest extends BaseDatabaseTestCase
{
    private const TEST_VENDOR_CODE = 'TVC';

    private ImportOfferUseCase $sut;

    private OfferRepository $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->entityManager->getRepository(Offer::class);

        $this->sut = $this->container->get(ImportOfferUseCase::class);
    }

    public function testOffersAreImported(): void
    {
        $this->assertEmpty($this->repository->findBy(['vendor_code' => self::TEST_VENDOR_CODE]));

        $initialPrice = 1000;
        $updatedPrice = 2000;

        $offer = new Offer();

        $offer->setVendorCode(self::TEST_VENDOR_CODE)
            ->setVendorId(123)
            ->setPrice($initialPrice)
            ->setName("Some test item")
            ->setBrand("PRICES");

        $result = $this->sut->import($offer);

        $this->assertTrue($result->isSuccess());
        $this->assertEquals(ImportResult::NEW_OFFER_CREATED, $result->getDetail());

        $existingOffer = $this->repository->findOneBy([
            'vendor_code' => self::TEST_VENDOR_CODE,
            'vendor_id' => $offer->getVendorId(),
        ]);

        $this->assertEquals($offer->getPrice(), $existingOffer->getPrice());
        $this->assertEquals($offer->getVendorId(), $existingOffer->getVendorId());
        $this->assertEquals($offer->getName(), $existingOffer->getName());

        $updatedOffer = new Offer();

        $updatedOffer->setVendorCode(self::TEST_VENDOR_CODE)
            ->setVendorId(123)
            ->setPrice($updatedPrice)
            ->setName("Some test item")
            ->setBrand("PRICES");

        $result = $this->sut->import($updatedOffer);

        $this->assertTrue($result->isSuccess());
        $this->assertEquals(ImportResult::EXISTING_OFFER_PRICE_CHANGED, $result->getDetail());

        $this->entityManager->refresh($updatedOffer);

        $this->assertEquals($updatedPrice, $updatedOffer->getPrice());
    }

    public function testEventIsDispatchedIfPriceHasChanged(): void
    {
        $existingPrice = 2000;
        $newPrice = 1000;

        $existingOffer = new Offer();
        $existingOffer->setId(12)->setPrice($existingPrice);

        $importedOffer = new Offer();
        $importedOffer->setPrice($newPrice);

        $repository = $this->createMock(OfferRepository::class);
        $repository->method('findOneBy')->willReturn($existingOffer);

        $priceChangeRepository = $this->createMock(PriceChangeRepository::class);
        $priceChangeRepository->expects($this->once())->method('save');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')->willReturnOnConsecutiveCalls($repository, $priceChangeRepository);

        $dispatcher = $this->createMock(EventDispatcher::class);
        $dispatcher->expects($this->once())->method('dispatch');

        $sut = new ImportOfferUseCase($entityManager, $dispatcher);

        $result = $sut->import($importedOffer);

        $this->assertTrue($result->isSuccess());

        $this->assertEquals(ImportResult::EXISTING_OFFER_PRICE_CHANGED, $result->getDetail());
    }

    private function clear(): void
    {
        $existingOffers = $this->repository->findBy([
            'vendor_code' => self::TEST_VENDOR_CODE,
        ]);

        foreach ($existingOffers as $existingOffer) {
            $this->entityManager->remove($existingOffer);
        }

        $this->entityManager->flush();
    }
}
