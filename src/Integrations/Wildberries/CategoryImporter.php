<?php

declare(strict_types=1);

namespace App\Integrations\Wildberries;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CategoryImporter
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $categoryId
     * @return \Iterator
     */
    public function getPageIterator(string $categoryId): \Iterator
    {
        return new ActiveCategoryIterator($this->client, $categoryId);
    }
}
