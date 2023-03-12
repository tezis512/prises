<?php

declare(strict_types=1);

namespace App\UseCases\Products;

use App\Entity\Offer;
use App\Entity\Product;

class GetProductForOfferUseCase
{
    /**
     * @param Offer $offer
     * @return array<Product>
     */
    public function getProducts(Offer $offer): array
    {
        // Levenshtein

        // Jaro Winkler

        // Smith Waterman Gotoh

        return [];
    }
}
