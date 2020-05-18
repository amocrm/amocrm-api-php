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

## Подход к работе с библиотекой
```todo```

## Поддерживаемые методы и сервисы

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

Для большинства сервисов есть базовый набор методов:

1. getOne - Получить 1 сущность
    1. id (int|string) - id сущности
    2. with (array) - массив параметров with, которые поддерживает модель сервиса
    3. Результатом выполнения будет модель сущности
    ```php
    getOne($id, array $with => []);
    ```

2. get Получить несколько сущностей:
    1. filter (BaseEntityFilter) - фильтр для сущности
    2. with (array) - массив параметров with, которые поддерживает модель сервиса
    3. Результатом выполнения будет коллекция сущностей
    ```php
    get(BaseEntityFilter $filter = null, array $with = []);
    ```
   
3. addOne Создать одну сущность:
    1. model (BaseApiModel) - модель создаваемой сущности
    2. Результатом выполнения будет модель сущности
    ```php
    addOne(BaseApiModel $model);
    ```
   
4. add Создать сущности пакетно:
    1. collection (BaseApiCollection) - коллекция моделей создаваемой сущности
    2. Результатом выполнения будет коллекция моделей сущности
    ```php
    add(BaseApiCollection $collection);
    ```

5. updateOne Обновить одну сущность:
    1. model (BaseApiModel) - модель создаваемой сущности
    2. Результатом выполнения будет модель сущности
    ```php
    updateOne(BaseApiModel $model);
    ```
   
6. update Обновить сущности пакетно:
    1. collection (BaseApiCollection) - коллекция моделей создаваемой сущности
    2. Результатом выполнения будет коллекция моделей сущности
    ```php
    update(BaseApiCollection $collection);
    ```
   
7. syncOne Синхронизировать одну модель с сервером:
    1. model (BaseApiModel) - коллекция моделей создаваемой сущности
    2. with (array) - массив параметров with, которые поддерживает модель сервиса
    4. Результатом выполнения будет коллекция моделей сущности
    ```php
    syncOne(BaseApiModel $model, $with = []);
    ```
   
Не все методы доступны во всех сервисах. В случае их вызова будет выброшены Exception.

Некоторые сервисы имеют специфичные методы, ниже рассмотрим сервисы, которые имеют специфичные методы.

```todo```

## Обработка ошибок

Вызов методов библиотеки может выбрасывать ошибки типа ```AmoCRMApiException```.
В данные момент доступны следующие типы ошибок, они все наследуют AmoCRMApiException:

|Тип                                                 |Условия                                                                                              |
|----------------------------------------------------|-----------------------------------------------------------------------------------------------------|
|AmoCRM\Exceptions\AmoCRMApiConnectExceptionException|Подключение к серверу не было выполнено                                                              |
|AmoCRM\Exceptions\AmoCRMApiErrorResponseException   |Сервер вернул ошибку на выполняемый запрос                                                           |
|AmoCRM\Exceptions\AmoCRMApiHttpClientException      |Произошла ошибка http клиента                                                                        |
|AmoCRM\Exceptions\AmoCRMApiNoContentException       |Сервер вернул код 204 без результата, ничего страшного не произошло, просто нет данных на ваш запрос |
|AmoCRM\Exceptions\AmoCRMApiTooManyRedirectsException|Слишком много редиректов (в нормальном режиме не выкидывается)                                       |
|AmoCRM\Exceptions\AmoCRMoAuthApiException           |Ошибка в oAuth клиенте                                                                               |
|AmoCRM\Exceptions\BadTypeException                  |Передан не верный тип данных                                                                         |
|AmoCRM\Exceptions\InvalidArgumentException          |Передан не верный аргумент                                                                           |
|AmoCRM\Exceptions\NotAvailableForActionException    |Метод не доступен для вызова                                                                         |

У выброшенных Exception есть следующие методы:
1. ```getErrorCode()```
2. ```getTitle()```
3. ```getLastRequestInfo()```
4. ```getDescription()```

У ошибки типа AmoCRMApiErrorResponseException есть метод ```getValidationErrors()```, который вернет ошибки валидации входящих данных.

## Фильтры

```todo```

## Примеры
В рамках данного репозитория имеется папка examples с различными примерами.

Для их работы необходимо добавить в неё файл .env со следующим содержимым, указав ваши значения:
```dotenv
CLIENT_ID=
CLIENT_SECRET=
CLIENT_REDIRECT_URI=
```

Затем вы можете поднять локальный сервер командой ```composer serve```. После конфигурацию необходимо перейти в браузере на страницу
```http://localhost:8181/examples/get_token.php``` для получения Access Token.
Для получения доступа к вашему локальному серверу из вне, можно использовать сервис ngrok.io. 

После авторизации вы можете проверить работу примеров обращаясь к ним из браузера. Стоит отметить, что для корректной работы примеров
необходимо проверить ID сущностей в них.

## License

MIT
