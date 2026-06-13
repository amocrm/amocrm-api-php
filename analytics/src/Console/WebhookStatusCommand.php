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

/**
 * CLI command for checking webhook status
 */
class WebhookStatusCommand extends Command
{
    protected static $defaultName = 'webhook:status';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Webhook Status');

        try {
            $config = Config::fromEnv();
            $tokenStorage = new FileTokenStorage($config->get('token_storage.path'));
            $amocrmFactory = new AmoCRMFactory($config, $tokenStorage);

            $client = $amocrmFactory->createClient();
            $webhooksService = $client->webhooks();

            $io->info('Fetching active webhooks...');

            $webhooks = $webhooksService->get();

            if ($webhooks === null || $webhooks->isEmpty()) {
                $io->warning('No webhooks subscribed.');
            } else {
                $io->table(
                    ['ID', 'URL', 'Events'],
                    array_map(fn($w) => [$w->getId(), $w->getSettings()->getWebhookUrl() ?? 'N/A', implode(', ', $w->getSettings()->getSupportedEventTypes() ?? [])], $webhooks->all())
                );
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
