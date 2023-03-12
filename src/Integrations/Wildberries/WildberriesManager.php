<?php

declare(strict_types=1);

namespace App\Integrations\Wildberries;

use App\Contracts\IntegrationManagerInterface;
use App\UseCases\Offers\ImportOfferUseCase;

class WildberriesManager implements IntegrationManagerInterface
{
    // https://static-basket-01.wb.ru/vol0/data/main-menu-ru-ru-v2.json
    // https://catalog.wb.ru/catalog/housecraft8/catalog?appType=1&cat=9735&couponsGeo=12,3,18,15,21&curr=rub&dest=-1257786&emp=0&lang=ru&locale=ru&pricemarginCoeff=1.0&reg=0&regions=80,64,38,4,83,33,70,68,69,30,86,75,40,1,66,31,48,110,22,71&sort=popular&spp=0
    // https://catalog.wb.ru/catalog/housecraft8/catalog?appType=1&cat=9735&couponsGeo=12,3,18,15,21&curr=rub&dest=-1257786&emp=0&lang=ru&locale=ru&page=2&pricemarginCoeff=1.0&reg=0&regions=80,64,38,4,83,33,70,68,69,30,86,75,40,1,66,31,48,110,22,71&sort=popular&spp=0

    private CategoryImporter $importer;

    private WildberriesItemParser $parser;

    private ImportOfferUseCase $offerManager;

    public function __construct(
        CategoryImporter $importer,
        WildberriesItemParser $parser,
        ImportOfferUseCase $offerManager
    )
    {
        $this->importer = $importer;
        $this->parser = $parser;
        $this->offerManager = $offerManager;
    }

    /**
     * @inheritDoc
     */
    public static function getVendorCode(): string
    {
        return WildberriesItemParser::getVendorCode();
    }

    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @inheritDoc
     */
    public function importCategory(string $categoryId): void
    {
        // 9735
        $iterator = $this->importer->getPageIterator($categoryId);

        foreach ($iterator as $page => $content) {

            echo "Importing category page {$page} \n";

            $offers = $this->parser->getParsedOffers($content);

            foreach ($offers as $offer) {
                try {
                    $result = $this->offerManager->import($offer);
                } catch (\Exception $exception) {
                    // Handle error here
                }

//                if ($result->isSuccess()) {
//
//                }

                echo "{$offer->getId()}: {$offer->getVendorId()} {$offer->getVendorCode()} {$offer->getName()} {$offer->getPrice()} \n";
            }
        }
    }
}
