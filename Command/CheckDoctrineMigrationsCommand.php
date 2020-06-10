<?php

namespace Alabama\CheckDoctrineMigrations\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckDoctrineMigrationsCommand extends Command
{
    public function configure()
    {
        $this
            ->setName('doctrine:migrations:check')
            ->setDescription('Returns 0 code if all migrations are runned, 1 code otherwise');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Yes, I am.!');
    }
}
