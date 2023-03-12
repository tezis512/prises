<?php

declare(strict_types=1);

namespace App\DataObject;

class ImportResult extends Result
{
    public const NEW_OFFER_CREATED = 'offer.new.created';

    public const EXISTING_OFFER_NO_CHANGE = 'offer.existing.no_change';

    public const EXISTING_OFFER_PRICE_CHANGED = 'offer.existing.price.changed';

    protected string $detail;

    public function __construct(bool $success, string $detail)
    {
        parent::__construct($success);

        $this->detail = $detail;
    }


    /**
     * @return string
     */
    public function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * @param string $detail
     * @return ImportResult
     */
    public function setDetail(string $detail): ImportResult
    {
        $this->detail = $detail;

        return $this;
    }
}
