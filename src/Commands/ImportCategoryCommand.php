<?php

declare(strict_types=1);

namespace App\Commands;

use App\Integrations\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'import:category')]
class ImportCategoryCommand extends Command
{
    private Factory $factory;

    public function __construct(Factory $factory)
    {
        parent::__construct();

        $this->factory = $factory;
    }

    protected function configure(): void
    {
        $this->addArgument('vendorCode', InputArgument::REQUIRED, 'Vendor code')
            ->addArgument('categoryId', InputArgument::REQUIRED, 'Category ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $vendorCode = $input->getArgument('vendorCode');
        $categoryId = $input->getArgument('categoryId');

        $output->writeln('Vendor code: ' . $vendorCode);
        $output->writeln('Category ID: ' . $categoryId);

        // Get Integration Manager from Factory by Vendor Code

        $manager = $this->factory->getManager($vendorCode);

        // throw if $manager is empty

        $manager->importCategory($categoryId);

        return Command::SUCCESS;
    }
}
