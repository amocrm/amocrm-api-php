<?php

declare(strict_types=1);

namespace Analytics\Webhook\Processors;

use Analytics\Webhook\ProcessorInterface;
use Analytics\Repository\TransactionRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\Models\Transaction;
use Carbon\Carbon;

/**
 * Processor for customer_transaction_added event
 */
class CustomerTransactionProcessor implements ProcessorInterface
{
    private TransactionRepo $transactionRepo;
    private AnalyticsLogger $logger;

    public function __construct(TransactionRepo $transactionRepo, AnalyticsLogger $logger)
    {
        $this->transactionRepo = $transactionRepo;
        $this->logger = $logger;
    }

    public function getEventType(): string
    {
        return 'customer_transaction_added';
    }

    public function process(array $payload): void
    {
        $data = $payload['payload'] ?? [];

        if (!isset($data['id'])) {
            throw new \InvalidArgumentException('Missing transaction ID in payload');
        }

        $transaction = new Transaction();
        $transaction->setTransactionId((int)$data['id']);
        $transaction->setCustomerId((int)($data['customer_id'] ?? 0));
        $transaction->setPrice((float)($data['price'] ?? 0));
        $transaction->setCreatedAt($this->parseTimestamp($data['created_at'] ?? null));
        $transaction->setCompletedAt($this->parseTimestamp($data['updated_at'] ?? null));
        $transaction->setIsCompleted(true);

        // Extract catalog element info
        $catalogElements = $data['catalog_elements'] ?? [];
        if (!empty($catalogElements) && isset($catalogElements[0])) {
            $element = $catalogElements[0];
            $transaction->setCatalogElementId($element['id'] ?? null);
            $transaction->setCatalogElementName($element['name'] ?? null);
            $transaction->setQuantity($element['quantity'] ?? 1);
            $transaction->setUnitPrice($element['price'] ?? null);
        }

        $this->transactionRepo->insert($transaction);

        $this->logger->webhook($this->getEventType(), (string)$data['id'], [
            'customer_id' => $transaction->getCustomerId(),
            'price' => $transaction->getPrice(),
        ]);
    }

    private function parseTimestamp(mixed $value): ?Carbon
    {
        if ($value === null) {
            return null;
        }

        if (is_numeric($value)) {
            return Carbon::createFromTimestamp((int)$value);
        }

        return Carbon::parse((string)$value);
    }
}
