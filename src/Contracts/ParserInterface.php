<?php

namespace App\Contracts;

interface ParserInterface
{
    /**
     * @param string $html
     * @return mixed
     */
    public function getParsedOffer($html);
}
