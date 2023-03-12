<?php

declare(strict_types=1);

namespace App\Contracts;

interface IntegrationManagerInterface
{
    /**
     * @return string
     */
    public static function getVendorCode(): string;

    /**
     * @param string $categoryId
     */
    public function importCategory(string $categoryId): void;
}
