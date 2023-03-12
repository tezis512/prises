<?php

declare(strict_types=1);

namespace App\Contracts;

interface StringSimilarityProcessorInterface
{
    /**
     * @param string $compareFrom
     * @param string $compareTo
     * @return int
     */
    public function getSimilarity(string $compareFrom, string $compareTo): int;
}
