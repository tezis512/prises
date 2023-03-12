<?php

declare(strict_types=1);

namespace App\UseCases\Products\StringSimilarityProcessors;

use App\Contracts\StringSimilarityProcessorInterface;

class SimilarWordsProcessor implements StringSimilarityProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function getSimilarity(string $compareFrom, string $compareTo): int
    {
        $clearedCompareFrom = str_replace([',', '.'], '', mb_strtolower($compareFrom));
        $clearedCompareTo = str_replace([',', '.'], '', mb_strtolower($compareTo));

        $explodedCompareFrom = explode(' ', $clearedCompareFrom);
        $explodedCompareTo = explode(' ', $clearedCompareTo);

        sort($explodedCompareFrom);
        sort($explodedCompareTo);

        if ($explodedCompareFrom == $explodedCompareTo) {
            return 100;
        }

        $compared = [];

        foreach ($explodedCompareFrom as $compareFromWord) {
//            $minimumDistance = -1; // For the future needs

            foreach ($explodedCompareTo as $compareToWord) {
                $distance = $this->getDistance($compareFromWord, $compareToWord);
                if ($distance === 0) {
                    $compared[] = $compareFromWord;
                    break;
                }
            }
        }

        $onePercent = count($explodedCompareFrom) / 100;
        $difference = count($compared) / $onePercent;

        return (int) $difference;
    }

    private function getDistance(string $compareFrom, string $compareTo): int
    {
        return levenshtein($compareFrom, $compareTo);
    }
}
