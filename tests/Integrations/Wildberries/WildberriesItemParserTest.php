<?php

namespace App\Tests\Integrations\Wildberries;

use App\Integrations\Wildberries\WildberriesItemParser;
use PHPUnit\Framework\TestCase;

class WildberriesItemParserTest extends TestCase
{
    public function testSomething(): void
    {
        $sut = new WildberriesItemParser();

        $html = '{
          "state": 0,
          "version": 2,
          "params": {
            "curr": "rub",
            "spp": 0,
            "version": 1
          },
          "data": {
            "products": [
              {
                "__sort": 42238,
                "ksort": 955,
                "time1": 4,
                "time2": 22,
                "dist": 47,
                "id": 6907947,
                "root": 5443391,
                "kindId": 0,
                "subjectId": 3538,
                "subjectParentId": 1077,
                "name": "Средство для унитаза, от известкового налета WC-Gel 1000 мл",
                "brand": "GRASS",
                "brandId": 14733,
                "siteBrandId": 24733,
                "supplierId": 28869,
                "sale": 50,
                "priceU": 60200,
                "salePriceU": 30100,
                "logisticsCost": 0,
                "saleConditions": 0,
                "pics": 8,
                "rating": 5,
                "feedbacks": 1789,
                "volume": 22,
                "colors": [],
                "sizes": [
                  {
                    "name": "",
                    "origName": "0",
                    "rank": 0,
                    "optionId": 24077138,
                    "wh": 507,
                    "sign": "5qEDehySroGY0Elendzx41LS7c0="
                  }
                ],
                "diffPrice": false
              },
              {
                "__sort": 42222,
                "ksort": 919,
                "time1": 4,
                "time2": 22,
                "dist": 47,
                "id": 9115912,
                "root": 7019123,
                "kindId": 0,
                "subjectId": 2665,
                "subjectParentId": 1077,
                "name": "Туалетный блок Колор актив с хлор-компонентом 3х50г",
                "brand": "Bref",
                "brandId": 10286,
                "siteBrandId": 20286,
                "supplierId": 10839,
                "sale": 30,
                "priceU": 49900,
                "salePriceU": 34900,
                "logisticsCost": 0,
                "saleConditions": 0,
                "pics": 9,
                "rating": 5,
                "feedbacks": 1107,
                "panelPromoId": 156202,
                "promoTextCat": "БОЛЬШИЕ СКИДКИ",
                "volume": 12,
                "colors": [
                  {
                    "name": "синий",
                    "id": 255
                  }
                ],
                "sizes": [
                  {
                    "name": "",
                    "origName": "0",
                    "rank": 0,
                    "optionId": 30502095,
                    "wh": 507,
                    "sign": "kS6fNG2930aw7vipzusIY3HRYQA="
                  }
                ],
                "diffPrice": false
              }
            ]
          }
        }';

        $offers = $sut->getParsedOffers($html);

        $this->assertCount(2, $offers);

        $this->assertEquals('6907947', $offers[0]->getVendorId());
        $this->assertEquals(WildberriesItemParser::getVendorCode(), $offers[0]->getVendorCode());
        $this->assertEquals('Средство для унитаза, от известкового налета WC-Gel 1000 мл', $offers[0]->getName());
        $this->assertEquals('GRASS', $offers[0]->getBrand());
        $this->assertEquals(30100, $offers[0]->getPrice());

        $this->assertEquals('9115912', $offers[1]->getVendorId());
        $this->assertEquals(WildberriesItemParser::getVendorCode(), $offers[1]->getVendorCode());
        $this->assertEquals('Туалетный блок Колор актив с хлор-компонентом 3х50г', $offers[1]->getName());
        $this->assertEquals(34900, $offers[1]->getPrice());
    }
}
