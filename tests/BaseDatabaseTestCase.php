<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BaseDatabaseTestCase extends KernelTestCase
{
    protected ContainerInterface $container;

    protected EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->container = $kernel->getContainer();

        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->entityManager->beginTransaction();
    }

    public function tearDown(): void
    {
        $this->entityManager->rollback();

        parent::tearDown();
    }
}
