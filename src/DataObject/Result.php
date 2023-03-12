<?php

declare(strict_types=1);

namespace App\DataObject;

class Result
{
    protected bool $success;

    public function __construct(bool $success = true)
    {
        $this->success = $success;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }
}
