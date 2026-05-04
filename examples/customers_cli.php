<?php

use AmoCRM\Client\LongLivedAccessToken;
use AmoCRM\Exceptions\AmoCRMApiErrorResponseException;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\Customers\CustomerModel;

include_once __DIR__ . '/bootstrap.php';

$longLivedToken = $_ENV['LONG_LIVED_ACCESS_TOKEN'] ?? getenv('LONG_LIVED_ACCESS_TOKEN') ?: '';
$accountBaseDomain = $_ENV['ACCOUNT_BASE_DOMAIN'] ?? getenv('ACCOUNT_BASE_DOMAIN') ?: '';
$accountProtocol = $_ENV['ACCOUNT_PROTOCOL'] ?? getenv('ACCOUNT_PROTOCOL') ?: 'https://';

if ($longLivedToken === '' || $accountBaseDomain === '') {
    echo "Set LONG_LIVED_ACCESS_TOKEN and ACCOUNT_BASE_DOMAIN in examples/.env.\n";
    exit(1);
}

if (!in_array($accountProtocol, ['https://', 'http://'], true)) {
    echo "ACCOUNT_PROTOCOL must be either https:// or http://.\n";
    exit(1);
}

$apiClient = new \AmoCRM\Client\AmoCRMApiClient();
try {
    $longLivedAccessToken = new LongLivedAccessToken($longLivedToken);
} catch (\AmoCRM\Exceptions\InvalidArgumentException $e) {
    echo "Invalid long-lived token: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
$apiClient->setAccessToken($longLivedAccessToken)
    ->setAccountBaseDomain($accountBaseDomain);
$apiClient->getOAuthClient()->setProtocol($accountProtocol);

function usage(): void
{
    $script = basename(__FILE__);
    echo "Usage:\n";
    echo "  php examples/{$script} list [limit]\n";
    echo "  php examples/{$script} get <customer_id>\n";
    echo "  php examples/{$script} create <name> [next_price] [ltv] [average_check]\n";
    echo "  php examples/{$script} update <customer_id> [next_price] [ltv] [average_check]\n";
}

function printApiException(AmoCRMApiException $e): void
{
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "Code: " . $e->getCode() . PHP_EOL;

    if ($e instanceof AmoCRMApiErrorResponseException) {
        echo "Validation errors:" . PHP_EOL;
        echo json_encode($e->getValidationErrors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }

    $lastRequestInfo = $e->getLastRequestInfo();
    if (!empty($lastRequestInfo)) {
        echo "Last request info:" . PHP_EOL;
        echo json_encode(
            [
                'last_http_method' => $lastRequestInfo['last_http_method'] ?? null,
                'last_method' => $lastRequestInfo['last_method'] ?? null,
                'last_body' => $lastRequestInfo['last_body'] ?? null,
                'last_query_params' => $lastRequestInfo['last_query_params'] ?? null,
                'last_response_code' => $lastRequestInfo['last_response_code'] ?? null,
                'last_response' => $lastRequestInfo['last_response'] ?? null,
                'last_request_id' => $lastRequestInfo['last_request_id'] ?? null,
                'curl_call' => $lastRequestInfo['curl_call'] ?? null,
            ],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ) . PHP_EOL;
    }
}

$command = $argv[1] ?? null;

if ($command === null) {
    usage();
    exit(1);
}

try {
    switch ($command) {
        case 'list':
            $limit = isset($argv[2]) ? max(1, (int) $argv[2]) : 10;
            $customers = $apiClient->customers()->get();

            if ($customers === null || $customers->isEmpty()) {
                echo "No customers found.\n";
                exit(0);
            }

            $count = 0;
            /** @var CustomerModel $customer */
            foreach ($customers as $customer) {
                printf(
                    "#%d | %s | next_price=%s | next_price_wmu=%.3F | ltv=%s | ltv_wmu=%.3F | avg_check=%s | avg_check_wmu=%.3F\n",
                    $customer->getId(),
                    $customer->getName() ?: '(no name)',
                    $customer->getNextPrice() !== null ? (string) $customer->getNextPrice() : 'null',
                    $customer->getNextPriceWithMinorUnits() !== null ? $customer->getNextPriceWithMinorUnits() : 'null',
                    $customer->getLtv() !== null ? (string) $customer->getLtv() : 'null',
                    $customer->getLtvWithMinorUnits() !== null ? $customer->getLtvWithMinorUnits() : 'null',
                    $customer->getAverageCheck() !== null ? (string) $customer->getAverageCheck() : 'null',
                    $customer->getAverageCheckWithMinorUnits() !== null ? $customer->getAverageCheckWithMinorUnits() : 'null'
                );

                $count++;
                if ($count >= $limit) {
                    break;
                }
            }
            break;

        case 'get':
            if (empty($argv[2])) {
                usage();
                exit(1);
            }

            $customerId = (int) $argv[2];
            /** @var CustomerModel|null $customer */
            $customer = $apiClient->customers()->getOne($customerId);

            if ($customer === null) {
                echo "Customer not found.\n";
                exit(1);
            }

            echo json_encode($customer->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
            break;

        case 'create':
            if (empty($argv[2])) {
                usage();
                exit(1);
            }

            $name = $argv[2];
            $nextPrice = $argv[3] ?? 0;
            $ltv = $argv[4] ?? 0;
            $averageCheck = $argv[5] ?? 0;

            if (!is_numeric((string) $nextPrice) || !is_numeric((string) $ltv) || !is_numeric((string) $averageCheck)) {
                echo "Prices/Amounts must be numeric." . PHP_EOL;
                exit(1);
            }

            $customer = (new CustomerModel())
                ->setName($name)
                ->setNextDate(time() + 86400) // API requires next_date
                ->setNextPrice((float) $nextPrice)
                ->setLtv((float) $ltv)
                ->setAverageCheck((float) $averageCheck);

            /** @var CustomerModel $createdCustomer */
            $createdCustomer = $apiClient->customers()->addOne($customer);

            printf(
                "Created customer: id=%d, name=%s, next_price=%s, next_price_wmu:%.3F, ltv=%s, ltv_wmu:%.3F, avg_check=%s, avg_check_wmu:%.3F\n",
                $createdCustomer->getId(),
                $createdCustomer->getName() ?: '(no name)',
                $createdCustomer->getNextPrice() !== null ? (string) $createdCustomer->getNextPrice() : 'null',
                $createdCustomer->getNextPriceWithMinorUnits() !== null ? $createdCustomer->getNextPriceWithMinorUnits() : 'null',
                $createdCustomer->getLtv() !== null ? (string) $createdCustomer->getLtv() : 'null',
                $createdCustomer->getLtvWithMinorUnits() !== null ? $createdCustomer->getLtvWithMinorUnits() : 'null',
                $createdCustomer->getAverageCheck() !== null ? (string) $createdCustomer->getAverageCheck() : 'null',
                $createdCustomer->getAverageCheckWithMinorUnits() !== null ? $createdCustomer->getAverageCheckWithMinorUnits() : 'null'
            );
            break;

        case 'update':
            if (empty($argv[2])) {
                usage();
                exit(1);
            }

            $customerId = (int) $argv[2];
            $nextPrice = $argv[3] ?? null;
            $ltv = $argv[4] ?? null;
            $averageCheck = $argv[5] ?? null;

            if (
                ($nextPrice !== null && !is_numeric((string) $nextPrice)) ||
                ($ltv !== null && !is_numeric((string) $ltv)) ||
                ($averageCheck !== null && !is_numeric((string) $averageCheck))
            ) {
                echo "Prices/Amounts must be numeric." . PHP_EOL;
                exit(1);
            }

            /** @var CustomerModel|null $customer */
            $customer = $apiClient->customers()->getOne($customerId);
            if ($customer === null) {
                echo "Customer not found.\n";
                exit(1);
            }

            if ($nextPrice !== null) {
                $customer->setNextPrice((float) $nextPrice);
            }
            if ($ltv !== null) {
                $customer->setLtv((float) $ltv);
            }
            if ($averageCheck !== null) {
                $customer->setAverageCheck((float) $averageCheck);
            }

            /** @var CustomerModel $updatedCustomer */
            $updatedCustomer = $apiClient->customers()->updateOne($customer);

            printf(
                "Updated customer: id=%d, name=%s, next_price=%s, next_price_wmu:%.3F, ltv=%s, ltv_wmu:%.3F, avg_check=%s, avg_check_wmu:%.3F\n",
                $updatedCustomer->getId(),
                $updatedCustomer->getName() ?: '(no name)',
                $updatedCustomer->getNextPrice() !== null ? (string) $updatedCustomer->getNextPrice() : 'null',
                $updatedCustomer->getNextPriceWithMinorUnits() !== null ? $updatedCustomer->getNextPriceWithMinorUnits() : 'null',
                $updatedCustomer->getLtv() !== null ? (string) $updatedCustomer->getLtv() : 'null',
                $updatedCustomer->getLtvWithMinorUnits() !== null ? $updatedCustomer->getLtvWithMinorUnits() : 'null',
                $updatedCustomer->getAverageCheck() !== null ? (string) $updatedCustomer->getAverageCheck() : 'null',
                $updatedCustomer->getAverageCheckWithMinorUnits() !== null ? $updatedCustomer->getAverageCheckWithMinorUnits() : 'null'
            );
            break;

        default:
            usage();
            exit(1);
    }
} catch (AmoCRMApiException $e) {
    printApiException($e);
    exit(1);
}
