<?php

namespace App\Integrations\Wildberries;

use App\Entity\Offer;

class WildberriesItemParser
{
    protected const WILDBERRIES = 'WB';

    public static function getVendorCode(): string
    {
        return static::WILDBERRIES;
    }

    /**
     * @param string $html
     * @return array<Offer>
     */
    public function getParsedOffers(string $html): array
    {
        $decoded = json_decode($html, true);

        $parsedProducts = [];

        if (empty($decoded)) {
            return $parsedProducts;
        }

        $productsData = $decoded['data']['products'];

        foreach ($productsData as $product) {
            $parsedProducts[] = (new Offer())->setName($product['name'])
                ->setBrand($product['brand'])
                ->setVendorId($product['id'])
                ->setPrice((int) $product['salePriceU'])
                ->setVendorCode($this->getVendorCode());
        }

        return $parsedProducts;
    }
}
