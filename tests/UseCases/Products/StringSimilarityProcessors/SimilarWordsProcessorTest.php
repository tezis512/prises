<?php

namespace App\Tests\UseCases\Products\StringSimilarityProcessors;

use App\UseCases\Products\StringSimilarityProcessors\LevenshteinStringSimilarityProcessor;
use App\UseCases\Products\StringSimilarityProcessors\SimilarWordsProcessor;
use PHPUnit\Framework\TestCase;

class SimilarWordsProcessorTest extends TestCase
{
    private int $similarityIndex = 60;

    /**
     * @param string $compareFrom
     * @param string $compareTo
     * @param bool $isSimilar
     * @dataProvider provideData
     */
    public function testGetSimilarity(string $compareFrom, string $compareTo, bool $isSimilar): void
    {
        $sut = new SimilarWordsProcessor();

        $similarity = $sut->getSimilarity($compareFrom, $compareTo);

        $this->assertEquals($isSimilar, $this->isSimilar($similarity));
    }

    public function provideData(): array
    {
        return [
            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                true,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Domestos Ультра Блеск Чистящее средство для туалета 1500 мл',
                true,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Чистящее средство Domestos Ультра Блеск 1500 мл',
                true,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Domestos Ультра Блеск чистящее средство для туалета и ванной, эффективное отбеливание, 1500 мл',
                true,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Чистящее средство Доместос Domestos универсальное Ультра Блеск 1500 мл',
                true,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Чистящее средство Доместос Domestos универсальное Ультра Блеск 1500 мл',
                true,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Domestos Ультра Блеск 1500 мл',
                false,
            ],

            [
                'Domestos Ультра Блеск 1500 мл',
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                true,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Чистящее средство для кухни антижир Azelit анти жир 600 мл',
                false,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Средство/гель для унитаза/туалета от ржавчины/налета/камня',
                false,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Универсальное чистящее средство гель для унитаза и уборки',
                false,
            ],

            [
                'Domestos Ультра Блеск Чистящее средство для туалета и ванной 1500 мл',
                'Professional, чистящее средство для клининга, 5 л',
                false,
            ],

            [
                'Domestos Professional, профессиональное чистящее средство, дезинфицирующее, для клининга, 5 л',
                'Professional, чистящее средство для клининга, 5 л',
                true,
            ],

            [
                'Professional, чистящее средство для клининга, 5 л',
                'Domestos Professional, профессиональное чистящее средство, дезинфицирующее, для клининга, 5 л',
                true,
            ],

            [
                'Professional, чистящее средство для клининга, 5 л',
                'Grass Чистящее средство Gloss Professional, 0.6 л',
                false,
            ],

            [
                'Professional, чистящее средство для клининга, 5 л',
                'Средство Чистящее Domestos Professional Дезинфицирующее для Клининга 5 л',
                true,
            ],

            [
                'Средство Чистящее Domestos Professional Дезинфицирующее для Клининга 5 л',
                'Professional, чистящее средство для клининга, 5 л',
                true,
            ],

            [
                'Средство Чистящее Domestos Professional Дезинфицирующее для Клининга 5 л',
                'Средство для уборки туалета 5 л, ЛАЙМА PROFESSIONAL, гель с отбеливающим эффектом, 601612',
                false,
            ],
        ];
    }

    private function isSimilar(int $similarityIndex): bool
    {
        return $this->similarityIndex <= $similarityIndex;
    }
}
