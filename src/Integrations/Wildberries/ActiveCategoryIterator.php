<?php

declare(strict_types=1);

namespace App\Integrations\Wildberries;

use Iterator;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ActiveCategoryIterator implements Iterator
{
    private HttpClientInterface $client;

    private string $categoryId;

    private int $page = 1;

    private bool $hasNext = true;

    /**
     * Client can be replaced with a client that uses proxy servers to fetch content later
     *
     * @param HttpClientInterface $client
     * @param string $categoryId
     */
    public function __construct(HttpClientInterface $client, string $categoryId)
    {
        $this->client = $client;
        $this->categoryId = $categoryId;
    }

    public function current(): mixed
    {
        //https://catalog.wb.ru/catalog/housecraft8/catalog?appType=1&cat=9735&couponsGeo=12,3,18,15,21&curr=rub&dest=-1257786&emp=0&lang=ru&locale=ru&pricemarginCoeff=1.0&reg=0&regions=80,64,38,4,83,33,70,68,69,30,86,75,40,1,66,31,48,110,22,71&sort=popular&spp=0&page=101

        if (! $this->hasNext) {
            return null;
        }

        $content = $this->getContent();

        if (empty($content)) {
            $this->hasNext = false;
        }

        $decoded = json_decode($content, true);
        if (empty($decoded['data']['products'])) {
            $this->hasNext = false;
        }

        return $content;
    }

    public function next(): void
    {
        ++$this->page;
    }

    public function key(): mixed
    {
        return $this->page;
    }

    public function valid(): bool
    {
        return $this->hasNext;
    }

    public function rewind(): void
    {
        $this->page = 1;
        $this->hasNext = true;
    }

    protected function getContent(): string
    {
        $baseUrl = 'https://catalog.wb.ru/catalog/housecraft8/catalog';
        $queryParams = "?appType=1&cat={$this->categoryId}&couponsGeo=12,3,18,15,21&curr=rub&dest=-1257786&emp=0&lang=ru&locale=ru&pricemarginCoeff=1.0&reg=0&regions=80,64,38,4,83,33,70,68,69,30,86,75,40,1,66,31,48,110,22,71&sort=popular&spp=0&page={$this->page}";

        $categoryUrl = $baseUrl . $queryParams;

        $response = $this->client->request('GET', $categoryUrl);

        return $response->getContent();
    }
}
