<?php

namespace App\Tests\UseCases\Products\StringSimilarityProcessors;

use App\UseCases\Products\StringSimilarityProcessors\LevenshteinStringSimilarityProcessor;
use PHPUnit\Framework\TestCase;

class LevenshteinStringSimilarityProcessorTest extends TestCase
{
    /**
     * @param string $compareFrom
     * @param string $compareTo
     * @param int $expected
     * @dataProvider provideData
     */
    public function testGetSimilarity(string $compareFrom, string $compareTo, int $expected): void
    {
        $sut = new LevenshteinStringSimilarityProcessor();

        $similarity = $sut->getSimilarity($compareFrom, $compareTo);

        $this->assertEquals($expected, $similarity);
    }

    public function provideData(): array
    {
        return [
            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                100,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Domestos Ультра Блеск Чистящее средство для туалета 1500 мл',
                76,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Чистящее средство Domestos Ультра Блеск 1500 мл',
                13,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Domestos Ультра Блеск чистящее средство для туалета и ванной, эффективное отбеливание, 1500 мл',
                26,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Чистящее средство Доместос Domestos универсальное Ультра Блеск 1500 мл',
                0,
            ],
        ];
    }
}
