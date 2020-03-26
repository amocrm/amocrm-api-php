# amoCRM API Library

В данном пакете представлен API клиент с поддержкой основных сущностей и авторизацией по протоколу OAuth 2.0 в amoCRM.

## Установка

Установить можно с помощью composer:

```
composer require amocrm/amocrm-api-library
```

## Использование

Для начала использования вам необходимо создать объект бибилиотеки:
```php
$apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
```

Затем необходимо создать объект (`\League\OAuth2\Client\Token\AccessToken`) Access токена из вашего хранилища токенов и установить его в API клиент.

Также необходимо установить домен аккаунта amoCRM в виде СУБДОМЕН.amocrm.(ru/com).

Вы можете установить функцию-callback на событие обновления Access токена, если хотите дополнительно обрабатывать новый токен (например сохранять его в хранилище токенов):
```php
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
```

### Пример
В рамках данного репозитория имеется файл **index.php**, который реализует простейшую логику авторизации, сохранения токена, а также совершения запросов.
Для использования нужно указать корректные значения clientSecret и clientId при создании объекта API клиента, а также redirectUri, ведущий на вызов этого файла на вашем сервере.
Дальше для теста можно перейти на страницу index.php, после чего будет отображена кнопка для открытия модального окна авторизации приложения в amoCRM.
После получения доступов вы увидете имя пользователя на экране.
Если добавить GET параметр - request=1, то будет совершен запрос за информацией об аккаунте с сохраненным ранее токеном.