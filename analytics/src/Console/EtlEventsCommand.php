<?php

declare(strict_types=1);

namespace Analytics\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Analytics\Config\Config;
use Analytics\Client\AmoCRMFactory;
use Analytics\Client\FileTokenStorage;
use Analytics\Repository\ConnectionFactory;
use Analytics\Logger\AnalyticsLogger;
use Analytics\ETL\EventsExporter;
use Analytics\Repository\AnalyticsEventRepo;

/**
 * CLI command for events ETL export
 */
class EtlEventsCommand extends Command
{
    protected static $defaultName = 'etl:events';

    protected function configure(): void
    {
        $this
            ->setDescription('Export events from amoCRM');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Events Export');

        try {
            $config = Config::fromEnv();
            $tokenStorage = new FileTokenStorage($config->get('token_storage.path'));
            $amocrmFactory = new AmoCRMFactory($config, $tokenStorage);
            $connectionFactory = new ConnectionFactory($config);
            $logger = new AnalyticsLogger($config->get('logging.path'), $config->get('logging.level'));

            $client = $amocrmFactory->createClient();
            $db = $connectionFactory->getConnection();
            $eventRepo = new AnalyticsEventRepo($db);
            $eventsExporter = new EventsExporter($client, $eventRepo, $logger);

            $io->info('Running events export...');

            $count = $eventsExporter->fullExport();

            $io->success("{$count} events exported.");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
