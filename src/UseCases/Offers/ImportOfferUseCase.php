<?php

declare(strict_types=1);

namespace App\UseCases\Offers;

use App\DataObject\ImportResult;
use App\Entity\Category;
use App\Entity\Offer;
use App\Entity\PriceChange;
use App\Events\PriceChangeEvent;
use App\Repository\OfferRepository;
use App\Repository\PriceChangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ImportOfferUseCase
{
    private EntityManagerInterface $entityManager;

    private OfferRepository $productRepository;

    protected PriceChangeRepository $priceChangeRepository;

    private EventDispatcherInterface $dispatcher;

    /**
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $entityManager;

        $this->productRepository = $this->entityManager->getRepository(Offer::class);
        $this->priceChangeRepository = $this->entityManager->getRepository(PriceChange::class);

        $this->dispatcher = $dispatcher;
    }

    /**
     * Imports Offer and dispatches PriceChangeEvent in case if the old price differs from the new price
     *
     * @param Offer $offer
     * @param Category|null $category
     * @return ImportResult
     */
    public function import(Offer $offer, ?Category $category = null): ImportResult
    {
        $newPrice = $offer->getPrice();

        $existingOffer = $this->productRepository->findOneBy([
            'vendor_code' => $offer->getVendorCode(),
            'vendor_id' => $offer->getVendorId(),
        ]);

        // Connect offer to product

        if (empty($existingOffer)) {
            $this->entityManager->persist($offer);

            // Add offer to category

            $this->entityManager->flush();

            return new ImportResult(true, ImportResult::NEW_OFFER_CREATED);
        }

        // Add offer to category

        $offer->setId($existingOffer->getId());

        if ($newPrice !== $existingOffer->getPrice()) {
            $priceChange = new PriceChange();

            $priceChange->setDatetime(new \DateTime())
                ->setOfferId($offer->getId())
                ->setOldPrice($existingOffer->getPrice())
                ->setNewPrice($newPrice);

            $this->priceChangeRepository->save($priceChange);
            $this->productRepository->save($offer);

            $this->entityManager->flush();

            $this->dispatcher->dispatch(new PriceChangeEvent($priceChange));

            return new ImportResult(true, ImportResult::EXISTING_OFFER_PRICE_CHANGED);
        }

        return new ImportResult(true, ImportResult::EXISTING_OFFER_NO_CHANGE);
    }
}
