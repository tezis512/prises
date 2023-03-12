<?php

declare(strict_types=1);

namespace App\UseCases\Products\StringSimilarityProcessors;

use App\Contracts\StringSimilarityProcessorInterface;

class LevenshteinStringSimilarityProcessor implements StringSimilarityProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function getSimilarity(string $compareFrom, string $compareTo): int
    {
        $distance = levenshtein($compareFrom, $compareTo);

        $length = mb_strlen($compareFrom);

        $onePercent = ($length / 100);
        $diffPercentage = $distance / $onePercent;

        $similarity = (int) floor(100 - $diffPercentage);

        return $similarity < 0 ? 0 : $similarity;
    }
}
