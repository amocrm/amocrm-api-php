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
use Analytics\Repository\PipelineStagesRepo;
use Analytics\Logger\AnalyticsLogger;

/**
 * CLI command for syncing pipelines and stages
 */
class SyncPipelinesCommand extends Command
{
    protected static $defaultName = 'sync:pipelines';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Sync Pipelines');

        try {
            $config = Config::fromEnv();
            $tokenStorage = new FileTokenStorage($config->get('token_storage.path'));
            $amocrmFactory = new AmoCRMFactory($config, $tokenStorage);
            $connectionFactory = new ConnectionFactory($config);
            $logger = new AnalyticsLogger($config->get('logging.path'), $config->get('logging.level'));

            $client = $amocrmFactory->createClient();
            $db = $connectionFactory->getConnection();
            $pipelineRepo = new PipelineStagesRepo($db);

            $io->info('Fetching pipelines...');

            // Fetch pipelines with statuses
            $pipelinesCollection = $client->pipelines()->get();

            $count = 0;
            foreach ($pipelinesCollection as $pipeline) {
                $pipelineId = $pipeline->getId();
                $pipelineName = $pipeline->getName();
                $statuses = $pipeline->getStatuses();

                $io->section("Pipeline: {$pipelineName}");

                if ($statuses !== null) {
                    foreach ($statuses as $status) {
                        $pipelineRepo->upsert([
                            'pipeline_id' => $pipelineId,
                            'pipeline_name' => $pipelineName,
                            'status_id' => $status->getId(),
                            'status_name' => $status->getName(),
                            'sort_order' => $status->getSortOrder(),
                            'is_final' => in_array($status->getId(), [142, 143]), // Won or Lost
                            'final_type' => $status->getId() === 142 ? 'won' : ($status->getId() === 143 ? 'lost' : null),
                        ]);
                        $count++;
                    }
                }
            }

            $io->success("Synced {$count} pipeline stages.");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Sync failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
