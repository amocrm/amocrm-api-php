<?php

declare(strict_types=1);

namespace Analytics\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Analytics\Config\Config;
use Analytics\Client\AmoCRMFactory;
use Analytics\Client\FileTokenStorage;
use Analytics\Repository\ConnectionFactory;
use Analytics\Repository\LeadSnapshotRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\ETL\LeadsExporter;

/**
 * CLI command for full ETL export
 */
class EtlFullCommand extends Command
{
    protected static $defaultName = 'etl:full';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Full ETL Export');

        try {
            // Initialize components
            $config = Config::fromEnv();
            $tokenStorage = new FileTokenStorage($config->get('token_storage.path'));
            $amocrmFactory = new AmoCRMFactory($config, $tokenStorage);
            $connectionFactory = new ConnectionFactory($config);
            $logger = new AnalyticsLogger($config->get('logging.path'), $config->get('logging.level'));

            // Check configuration
            if (!$amocrmFactory->isConfigured()) {
                $io->error('AmoCRM is not configured. Please set AMOCRM_CLIENT_ID, AMOCRM_CLIENT_SECRET, and AMOCRM_BASE_DOMAIN');
                return Command::FAILURE;
            }

            // Create client and exporter
            $client = $amocrmFactory->createClient();
            $db = $connectionFactory->getConnection();
            $leadSnapshotRepo = new LeadSnapshotRepo($db);
            $leadsExporter = new LeadsExporter($client, $leadSnapshotRepo, $logger);

            $io->info('Starting full export...');

            // Export leads
            $count = $leadsExporter->fullExport();

            $io->success("Export complete. {$count} leads exported.");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Export failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
