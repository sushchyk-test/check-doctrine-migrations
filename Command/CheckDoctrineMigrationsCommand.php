<?php

namespace Alabama\CheckDoctrineMigrations\Command;

use Doctrine\Bundle\MigrationsBundle\Command\DoctrineCommand;
use Doctrine\Bundle\MigrationsBundle\Command\Helper\DoctrineCommandHelper;
use Doctrine\Migrations\Tools\Console\Command\AbstractCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CheckDoctrineMigrationsCommand extends AbstractCommand
{
    const TIMEOUT_OPTION = 'timeout';

    const SLEEP_OPTION = 'sleep';

    /** @var string */
    protected static $defaultName = 'doctrine:migrations:check';

    public function configure(): void
    {
        $this
            ->setAliases(['check'])
            ->setDescription('Check is all migrations are runned.')
            ->addOption('db', null, InputOption::VALUE_REQUIRED, 'The database connection to use for this command.')
            ->addOption('em', null, InputOption::VALUE_REQUIRED, 'The entity manager to use for this command.')
            ->addOption('shard', null, InputOption::VALUE_REQUIRED, 'The shard connection to use for this command.')
            ->addOption(self::TIMEOUT_OPTION, null, InputOption::VALUE_REQUIRED, 'The time wait for all migrations', '2 minutes')
            ->addOption(self::SLEEP_OPTION, null, InputOption::VALUE_REQUIRED, 'The time to sleep after single check', '10 seconds')
            ->addUsage(self::TIMEOUT_OPTION . "='1 minute'")
            ->addUsage(self::SLEEP_OPTION . "='5 seconds'")
            ->addUsage(self::TIMEOUT_OPTION . "='120 seconds' " . self::SLEEP_OPTION . "='1 minute'");

        parent::configure();
    }

    public function initialize(InputInterface $input, OutputInterface $output): void
    {
        /** @var Application $application */
        $application = $this->getApplication();

        DoctrineCommandHelper::setApplicationHelper($application, $input);

        $configuration = $this->getMigrationConfiguration($input, $output);
        $container = $application->getKernel()->getContainer();
        assert($container instanceof ContainerInterface);
        DoctrineCommand::configureMigrations($container, $configuration);

        parent::initialize($input, $output);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sleepOptionValue = $input->getOption(self::SLEEP_OPTION);
        $sleepSeconds = (new \DateTime('+' . $sleepOptionValue))->getTimestamp() - (new \DateTime('now'))->getTimestamp();

        $timeoutOptionValue = $input->getOption(self::TIMEOUT_OPTION);
        $dateTimeToWait = ((new \DateTime('+' . $timeoutOptionValue)))->modify('+1 second');

        while(new \DateTime('now') < $dateTimeToWait) {
            $newMigrationsCount = count($this->dependencyFactory->getMigrationRepository()->getNewVersions());
            if ($newMigrationsCount === 0) {
                return 0;
            }
            sleep($sleepSeconds);
        }

        return 1;
    }
}
