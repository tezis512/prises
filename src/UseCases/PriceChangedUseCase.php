<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Entity\PriceChange;
use App\Repository\PriceChangeRepository;

class PriceChangedUseCase
{
    protected PriceChangeRepository $repository;

    public function __construct(PriceChangeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function process(PriceChange $priceChange): void
    {
        // Get offer from Price Change
        // Get Vendor Code from Offer
        // Calculate percentage
        // Get Subscriptions for this Vendor and for this percentage
        //
    }
}
