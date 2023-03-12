<?php

declare(strict_types=1);

namespace App\Events;

use App\Entity\PriceChange;
use Symfony\Contracts\EventDispatcher\Event;

class PriceChangeEvent extends Event
{
    public const NAME = 'offer.price_changed';

    private PriceChange $priceChange;

    public function __construct(PriceChange $priceChange)
    {
        $this->priceChange = $priceChange;
    }

    /**
     * @return PriceChange
     */
    public function getPriceChange(): PriceChange
    {
        return $this->priceChange;
    }
}
