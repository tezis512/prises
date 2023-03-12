<?php

declare(strict_types=1);

namespace App\EventSybscribers;

use App\Events\PriceChangeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PriceChangedEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [PriceChangeEvent::NAME];
    }

    public function process(PriceChangeEvent $vent)
    {
//        var_dump('hello');
//        die();
    }
}
