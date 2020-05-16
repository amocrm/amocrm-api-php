# amoCRM API Library

В данном пакете представлен API клиент с поддержкой основных сущностей и авторизацией по протоколу OAuth 2.0 в amoCRM.

## Установка

Установить можно с помощью composer:

```
composer require amocrm/amocrm-api-library
```

## Начало работы (авторизация)

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
            function (\League\OAuth2\Client\Token\AccessTokenInterface $accessToken, string $baseDomain) {
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

Отправить пользователя на страницу авторизации можно 2мя способами:
1. Отрисовав кнопку на сайт:
```php
$apiClient->getOAuthClient()->getOAuthButton(
            [
                'title' => 'Установить интеграцию',
                'compact' => true,
                'class_name' => 'className',
                'color' => 'default',
                'error_callback' => 'handleOauthError',
                'state' => $state,
            ]
        );
```
2. Отправив пользователя на страницу авторизации
```php
$authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
            'state' => $state,
            'mode' => 'post_message', //post_message - редирект произойдет в открытом окне, popup - редирект произойдет в окне родителе
        ]);
        header('Location: ' . $authorizationUrl);
```

Для получения Access Token можно использовать следующий код в обработчике, который будет находится по адресу, указаному в redirect_uri
```php
$accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);
```

Пример авторизации можно посмотреть в файле examples/get_token.php

## Поддерживаемые методы

Библиотека поддерживает большое количество методов API. Методы сгруппированы и объекты-сервисы. Получить объект сервиса можно вызвав необходимый метод у библиотеки, например:
```php
$leadsService = $apiClient->leads();
```

В данный момент доступны следующие сервисы:

| Сервис            | Цель сервиса                  |
|-------------------|-------------------------------|
| notes             | Примечание сущности           |
| tags              | Теги сущностей                |
| tasks             | Задачи                        |
| leads             | Сделки                        |
| contacts          | Контакты                      |
| companies         | Компании                      |
| catalogs          | Каталоги                      |
| catalogElements   | Элементы каталогов            |
| customFields      | Пользовательские поля         |
| customFieldGroups | Группы пользовательских полей |
| account           | Информация об аккаунте        |
| roles             | Роли пользователей            |
| users             | Роли юзеров                   |
| customersSegments | Сегменты покупателей          |
| events            | Список событий                |
| webhooks          | Вебхуки                       | 
| unsorted          | Неразобранное                 |
| pipelines         | Воронки сделок                |
| statuses          | Статусы сделок                |
| widgets           | Виджеты                       |
| lossReason        | Причины отказа                |
| transactions      | Покупки покупателей           |
| customers         | Покупатели                    |
| customersStatuses | Сегменты покупателя           |
| getOAuthClient    | oAuth сервис                  |
| getRequest        | Голый запросы                 |


### Пример
В рамках данного репозитория имеется файл **index.php**, который реализует простейшую логику авторизации, сохранения токена, а также совершения запросов.
Для использования нужно указать корректные значения clientSecret и clientId при создании объекта API клиента, а также redirectUri, ведущий на вызов этого файла на вашем сервере.
Дальше для теста можно перейти на страницу index.php, после чего будет отображена кнопка для открытия модального окна авторизации приложения в amoCRM.
После получения доступов вы увидете имя пользователя на экране.
Если добавить GET параметр - request=1, то будет совершен запрос за информацией об аккаунте с сохраненным ранее токеном.