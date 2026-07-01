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
use Analytics\Webhook\ProcessorFactory;
use Analytics\Logger\AnalyticsLogger;

/**
 * CLI command for subscribing to webhooks
 */
class WebhookSubscribeCommand extends Command
{
    protected static $defaultName = 'webhook:subscribe';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Webhook Subscription');

        try {
            $config = Config::fromEnv();
            $tokenStorage = new FileTokenStorage($config->get('token_storage.path'));
            $amocrmFactory = new AmoCRMFactory($config, $tokenStorage);

            $client = $amocrmFactory->createClient();
            $webhooksService = $client->webhooks();

            $io->info('Available webhook events:');

            $events = [
                'lead_added',
                'lead_status_changed',
                'lead_updated',
                'lead_deleted',
                'contact_added',
                'contact_updated',
                'customer_added',
                'customer_transaction_added',
                'unsorted_added',
            ];

            foreach ($events as $event) {
                $io->listing([$event]);
            }

            // Get webhook URL from config
            $webhookUrl = $config->get('fusio.app_public') . '/webhook/amocrm';

            $io->info("Webhook URL: {$webhookUrl}");

            // Note: Actual subscription would require admin rights
            $io->note('Use amoCRM UI or API to subscribe to webhooks pointing to this URL.');

            $io->success('Webhook configuration ready.');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
