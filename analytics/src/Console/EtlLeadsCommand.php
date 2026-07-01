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
use Analytics\Repository\LeadSnapshotRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\ETL\LeadsExporter;

/**
 * CLI command for leads-only ETL export
 */
class EtlLeadsCommand extends Command
{
    protected static $defaultName = 'etl:leads';

    protected function configure(): void
    {
        $this
            ->setDescription('Export leads from amoCRM')
            ->addArgument('lead-id', InputArgument::OPTIONAL, 'Export single lead by ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Leads Export');

        try {
            $config = Config::fromEnv();
            $tokenStorage = new FileTokenStorage($config->get('token_storage.path'));
            $amocrmFactory = new AmoCRMFactory($config, $tokenStorage);
            $connectionFactory = new ConnectionFactory($config);
            $logger = new AnalyticsLogger($config->get('logging.path'), $config->get('logging.level'));

            $client = $amocrmFactory->createClient();
            $db = $connectionFactory->getConnection();
            $leadSnapshotRepo = new LeadSnapshotRepo($db);
            $leadsExporter = new LeadsExporter($client, $leadSnapshotRepo, $logger);

            $leadId = $input->getArgument('lead-id');

            if ($leadId !== null) {
                $io->info("Exporting lead #{$leadId}...");
                $snapshot = $leadsExporter->exportById((int)$leadId);
                if ($snapshot !== null) {
                    $io->success("Lead #{$leadId} exported successfully.");
                } else {
                    $io->warning("Lead #{$leadId} not found.");
                }
            } else {
                $io->info('Running full leads export...');
                $count = $leadsExporter->fullExport();
                $io->success("{$count} leads exported.");
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
