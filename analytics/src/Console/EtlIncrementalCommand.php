<?php

declare(strict_types=1);

namespace Analytics\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Analytics\Config\Config;
use Analytics\Client\AmoCRMFactory;
use Analytics\Client\FileTokenStorage;
use Analytics\Repository\ConnectionFactory;
use Analytics\Repository\LeadSnapshotRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\ETL\LeadsExporter;
use Carbon\Carbon;

/**
 * CLI command for incremental ETL export
 */
class EtlIncrementalCommand extends Command
{
    protected static $defaultName = 'etl:incremental';

    protected function configure(): void
    {
        $this
            ->setDescription('Run incremental ETL export since last sync')
            ->addOption('since', 's', InputOption::VALUE_OPTIONAL, 'Export changes since date (YYYY-MM-DD HH:MM:SS)', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Incremental ETL Export');

        try {
            $config = Config::fromEnv();
            $tokenStorage = new FileTokenStorage($config->get('token_storage.path'));
            $amocrmFactory = new AmoCRMFactory($config, $tokenStorage);
            $connectionFactory = new ConnectionFactory($config);
            $logger = new AnalyticsLogger($config->get('logging.path'), $config->get('logging.level'));

            if (!$amocrmFactory->isConfigured()) {
                $io->error('AmoCRM is not configured');
                return Command::FAILURE;
            }

            // Parse since date
            $since = $input->getOption('since');
            if ($since === null) {
                $since = Carbon::now()->subHours(1); // Default: last hour
            } else {
                $since = Carbon::parse($since);
            }

            $client = $amocrmFactory->createClient();
            $db = $connectionFactory->getConnection();
            $leadSnapshotRepo = new LeadSnapshotRepo($db);
            $leadsExporter = new LeadsExporter($client, $leadSnapshotRepo, $logger);

            $io->info("Exporting changes since: {$since->format('Y-m-d H:i:s')}");

            $count = $leadsExporter->incrementalExport($since);

            $io->success("Incremental export complete. {$count} leads updated.");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Export failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
