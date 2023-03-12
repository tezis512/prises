<?php

declare(strict_types=1);

namespace App\Integrations;

use App\Contracts\IntegrationManagerInterface;

class Factory
{
    /**
     * @var array<IntegrationManagerInterface>
     */
    private array $managers = [];

    /**
     * Factory constructor.
     * @param array $managers
     */
    public function __construct(array $managers)
    {
        foreach ($managers as $manager) {
            if (! $manager instanceof IntegrationManagerInterface) {
                throw new \InvalidArgumentException('Manager must be instance of IntegrationManagerInterface');
            }

            $this->managers[$manager->getVendorCode()] = $manager;
        }
    }

    /**
     * @param string $code
     * @return IntegrationManagerInterface|null
     */
    public function getManager(string $code): ?IntegrationManagerInterface
    {
        return $this->managers[$code] ?? null;
    }
}
