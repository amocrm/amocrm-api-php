<?php

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\EntitiesLinksFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\LinkModel;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . '/bootstrap.php';

$accessToken = getToken();

/** @var AmoCRMApiClient $apiClient */
$apiClient->setAccessToken($accessToken)
    ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
    ->onAccessTokenRefresh(
        static function (AccessTokenInterface $accessToken, string $baseDomain) {
            saveToken(
                [
                    'accessToken'  => $accessToken->getToken(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'expires'      => $accessToken->getExpires(),
                    'baseDomain'   => $baseDomain,
                ]
            );
        }
    );

$links = (new LinksCollection())
    ->add(
        (new LinkModel())
            ->setEntityId(2584271)
            ->setEntityType(EntityTypesInterface::LEADS)
            ->setToEntityId(3135527)
            ->setToEntityType(EntityTypesInterface::CONTACTS)
            ->setMetadata(['is_main' => true])
    )
    ->add(
        (new LinkModel())
            ->setEntityId(2584271)
            ->setEntityType(EntityTypesInterface::LEADS)
            ->setToEntityId(3100867)
            ->setToEntityType(EntityTypesInterface::COMPANIES)
    )
    ->add(
        (new LinkModel())
            ->setEntityId(2584269)
            ->setEntityType(EntityTypesInterface::LEADS)
            ->setToEntityId(3135527)
            ->setToEntityType(EntityTypesInterface::CONTACTS)
            ->setMetadata(['is_main' => true])
    )
    ->add(
        (new LinkModel())
            ->setEntityId(2584269)
            ->setEntityType(EntityTypesInterface::LEADS)
            ->setToEntityId(3100867)
            ->setToEntityType(EntityTypesInterface::COMPANIES)
    );

/** @noinspection PhpUnhandledExceptionInspection */
$linksService = $apiClient->links(EntityTypesInterface::LEADS);

try {
    $linksService->add($links);
} catch (AmoCRMApiException $exception) {
    printError($exception);
    die;
}

try {
    $filter = new EntitiesLinksFilter([2584269, 2584271]);
    $allLinks = $linksService->get($filter);
    echo json_encode($allLinks->toArray());
} catch (AmoCRMApiException $exception) {
    printError($exception);
    die;
}
