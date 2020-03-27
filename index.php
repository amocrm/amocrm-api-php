<?php

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\LeadFilter;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

define('TOKEN_FILE', DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'token_info.json');

include_once __DIR__ . '/vendor/autoload.php';

session_start();

$clientId = '106c7176-8f00-46dd-9040-9676ee933119';
$clientSecret = 'bvyCfriLuVIAe2D9qkF58xEncMnD3pgAmzkAPvAcPyUg7ayLtImJOceoIxPFmno0';
$redirectUri = 'http://localhost:8181/';

$apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

if (isset($_GET['referer'])) {
    $apiClient->setAccountBaseDomain($_GET['referer']);
}

if (!isset($_GET['request'])) {
    if (!isset($_GET['code'])) {
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth2state'] = $state;
        if (isset($_GET['button'])) {
            echo $apiClient->getOAuthClient()->getOAuthButton(
                [
                    'title' => 'Установить интеграцию',
                    'compact' => true,
                    'class_name' => 'className',
                    'color' => 'default',
                    'error_callback' => 'handleOauthError',
                    'state' => $state,
                ]
            );
            die;
        } else {
            $authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl($state);
            header('Location: ' . $authorizationUrl);
            die;
        }
    } elseif (empty($_GET['state']) || empty($_SESSION['oauth2state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
        exit('Invalid state');
    }

    /**
     * Ловим обратный код
     */
    try {
        $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);

        if (!$accessToken->hasExpired()) {
            saveToken([
                'accessToken' => $accessToken->getToken(),
                'refreshToken' => $accessToken->getRefreshToken(),
                'expires' => $accessToken->getExpires(),
                'baseDomain' => $apiClient->getAccountBaseDomain(),
            ]);
        }
    } catch (Exception $e) {
        die((string)$e);
    }

    $ownerDetails = $apiClient->getOAuthClient()->getResourceOwner($accessToken);

    printf('Hello, %s!', $ownerDetails->getName());
} else {
    $accessToken = getToken();

    $apiClient->setAccessToken($accessToken)
        ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
        ->onAccessTokenRefresh(
            function (AccessTokenInterface $accessToken, string $baseDomain) {
                saveToken(
                    [
                        'accessToken' => $accessToken->getToken(),
                        'refreshToken' => $accessToken->getRefreshToken(),
                        'expires' => $accessToken->getExpires(),
                        'baseDomain' => $baseDomain,
                    ]
                );
            });

    $filter = new LeadFilter();
    $filter->setIds([3912171, 3921175])
        ->setResponsibleUserIds([504141]);

    try {
        $leads = $apiClient->leads()->get($filter, [\AmoCRM\Models\LeadModel::IS_PRICE_BY_ROBOT]);
    } catch (AmoCRMApiException $e) {
        echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
        die;
    } catch (AmoCRMoAuthApiException $e) {
        echo 'Error happen - ' . $e->getMessage() . ' ' . $e->getCode();
        die;
    }

    var_dump($leads); die;
    foreach ($leads as $lead) {
        $lead->setName('qwrqwr');
        $lead->setPrice(12);
    }

    try {
        $apiClient->leads()->update($leads);
    } catch (AmoCRMApiException $e) {
        echo 'Error happen - ' . $e->getMessage() . $e->getTitle();
        die;
    } catch (AmoCRMoAuthApiException $e) {
        echo 'Error happen - ' . $e->getMessage();
        die;
    }
}

function saveToken($accessToken)
{
    if (
        isset($accessToken)
        && isset($accessToken['accessToken'])
        && isset($accessToken['refreshToken'])
        && isset($accessToken['expires'])
        && isset($accessToken['baseDomain'])
    ) {
        $data = [
            'accessToken' => $accessToken['accessToken'],
            'expires' => $accessToken['expires'],
            'refreshToken' => $accessToken['refreshToken'],
            'baseDomain' => $accessToken['baseDomain'],
        ];

        file_put_contents(TOKEN_FILE, json_encode($data));
    } else {
        exit('Invalid access token ' . var_export($accessToken, true));
    }
}

/**
 * @return AccessToken
 */
function getToken()
{
    if (!file_exists(TOKEN_FILE)) {
        exit('Access token file not found');
    }

    $accessToken = json_decode(file_get_contents(TOKEN_FILE), true);

    if (
        isset($accessToken)
        && isset($accessToken['accessToken'])
        && isset($accessToken['refreshToken'])
        && isset($accessToken['expires'])
        && isset($accessToken['baseDomain'])
    ) {
        return new AccessToken([
            'access_token' => $accessToken['accessToken'],
            'refresh_token' => $accessToken['refreshToken'],
            'expires' => $accessToken['expires'],
            'baseDomain' => $accessToken['baseDomain'],
        ]);
    } else {
        exit('Invalid access token ' . var_export($accessToken, true));
    }
}