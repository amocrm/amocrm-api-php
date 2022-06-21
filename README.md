![amoCRM API Library](.github/logo.png?raw=true)

# amoCRM API Library

[![Latest Version](https://img.shields.io/github/release/amocrm/amocrm-api-php)](https://github.com/amocrm/amocrm-api-php/releases)
[![Build Status](https://img.shields.io/travis/amocrm/amocrm-api-php)](https://travis-ci.org/github/amocrm/amocrm-api-php/)
[![Total Downloads](https://img.shields.io/packagist/dt/amocrm/amocrm-api-library.svg)](https://packagist.org/packages/amocrm/amocrm-api-library)

В данном пакете представлен API клиент с поддержкой основных сущностей и авторизацией по протоколу OAuth 2.0 в amoCRM. 
Для работы библиотеки требуется PHP версии не ниже 7.1.

## Оглавление
- [Установка](#установка)
- [Начало работы](#начало-работы-и-авторизация)
- [Подход к работе с библиотекой](#подход-к-работе-с-библиотекой)
- [Поддерживаемые методы и сервисы](#поддерживаемые-методы-и-сервисы)
- [Обработка ошибок](#обработка-ошибок)
- [Фильтры](#Фильтры)
- [Работа с Custom Fields сущностей](#работа-с-дополнительными-полями-сущностей)
- [Работа с тегами сущностей](#работа-с-тегами-сущностей)
- [Особенности работы с источниками](#особенности-работы-с-источниками)
- [Константы](#константы)
- [Работа в случае смены субдомена аккаунта](#Работа-в-случае-смены-субдомена-аккаунта)
- [Одноразовые токены интеграций, расшифровка](#одноразовые-токены-интеграций-расшифровка)
- [Работа с валютами](#работа-с-валютами)
- [Примеры](#примеры)

## Установка

Установить библиотеку можно с помощью composer:

```
composer require amocrm/amocrm-api-library
```

## Начало работы и авторизация

Для начала использования вам необходимо создать объект библиотеки:
```php
$apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
```

Также предоставляется фабрика для создания объектов `\AmoCRM\Client\AmoCRMApiClientFactory`.
Для ее использования вам нужно реализовать интерфейс `\AmoCRM\OAuth\OAuthConfigInterface` и `\AmoCRM\OAuth\OAuthServiceInterface`

```php
$apiClientFactory = new \AmoCRM\Client\AmoCRMApiClientFactory($oAuthConfig, $oAuthService);
$apiClient = $apiClientFactory->make();
```

При использовании фабрики вам не нужно устанавливать callback onAccessTokenRefresh, при обновлении токена будет вызван метод saveOAuthToken из $oAuthService (\AmoCRM\OAuth\OAuthServiceInterface).

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

Для получения Access Token можно использовать следующий код в обработчике, который будет находиться по адресу, указанному в redirect_uri
```php
$accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);
```

Пример авторизации можно посмотреть в файле examples/get_token.php

## Подход к работе с библиотекой

В библиотеке используется сервисный подход. Для каждой сущности имеется сервис.
Для каждого метода имеется свой объект коллекции и модели.
Работа с данными происходит через коллекции и методы библиотеки.

Модели и коллекции имеют методы ```toArray()``` и ```toApi()```, методы возвращают представление объекта в виде массива и в виде данных, отправляемых в API.

Также для работы с коллекциями имеются следующие методы:

1. ```add(BaseApiModel $model): self``` - добавляет модель в конец коллекции.
2. ```prepend(BaseApiModel $value): self``` - добавляет модель в начало коллекции.
3. ```all(): array``` - возвращает массив моделей в коллекции.
4. ```first(): ?BaseApiModel``` - получение первой модели в коллекции.
5. ```last(): ?BaseApiModel``` - получение последней модели в коллекции.
6. ```count(): int``` - получение кол-ва элементов в коллекции.
7. ```isEmpty(): bool``` - проверяет, что коллекция не пустая.
8. ```getBy($key, $value): ?BaseApiModel``` - получение модели по значению ключа.
9. ```replaceBy($key, $value, BaseApiModel $replacement): void``` - замена модели по значению ключа.
10. ```removeBy($key, $value): int``` - удаление моделей по значению ключа, возвращает количество удаленных моделей.
11. ```removeFirstBy($key, $value): bool``` - удаление первой модели по значению ключа, возвращает true если модель была удалена.
12. ```chunk(int $size): array``` - разделение коллекции на массив состоящий из коллекций определенной длины.
13. ```pluck(string $column): array``` - получение массива значений моделей коллекции по названию свойства.

При работе с библиотекой необходимо не забывать о лимитах API amoCRM.
Для оптимальной работы с данными лучше всего создавать/изменять за раз не более 50 сущностей в методах, где есть пакетная обработка.

Нужно не забывать про обработку ошибок, а также не забывать о безопасности хранилища токенов. **Утечка токена грозит потерей доступа к аккаунту.**

## Поддерживаемые методы и сервисы

Библиотека поддерживает большое количество методов API. Методы сгруппированы в объекты-сервисы. Получить объект сервиса можно вызвав необходимый метод у библиотеки, например:
```php
$leadsService = $apiClient->leads();
```

В данный момент доступны следующие сервисы:

| Сервис               | Цель сервиса                  |
|----------------------|-------------------------------|
| notes                | Примечание сущности           |
| tags                 | Теги сущностей                |
| tasks                | Задачи                        |
| leads                | Сделки                        |
| contacts             | Контакты                      |
| companies            | Компании                      |
| catalogs             | Каталоги                      |
| catalogElements      | Элементы каталогов            |
| customFields         | Пользовательские поля         |
| customFieldGroups    | Группы пользовательских полей |
| account              | Информация об аккаунте        |
| roles                | Роли пользователей            |
| users                | Роли юзеров                   |
| customersSegments    | Сегменты покупателей          |
| events               | Список событий                |
| webhooks             | Вебхуки                       | 
| unsorted             | Неразобранное                 |
| pipelines            | Воронки сделок                |
| statuses             | Статусы сделок                |
| widgets              | Виджеты                       |
| lossReason           | Причины отказа                |
| transactions         | Покупки покупателей           |
| customers            | Покупатели                    |
| customersStatuses    | Сегменты покупателя           |
| customersBonusPoints | Бонусные баллы покупателя     |
| calls                | Звонки                        |
| products             | Товары                        |
| links                | Массовая привязка сущностей   |
| shortLinks           | Короткие ссылки               |
| talks                | Беседы                        |
| sources              | Источники                     |
| chatTemplates        | Шаблоны чатов                 |
| entitySubscriptions  | Подписчики сущности           |
| getOAuthClient       | oAuth сервис                  |
| getRequest           | Голые запросы                 |

#### Для большинства сервисов есть базовый набор методов:

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

#### Методы доступные в сервисе ```leads```:
1. addComplex Создать сделки пакетно со связанным контакт и компанией через [комплексный метод](https://www.amocrm.ru/developers/content/crm_platform/leads-api#leads-complex-add) с поддержкой [контроля дублей](https://www.amocrm.ru/developers/content/crm_platform/duplication-control)
    1. collection (LeadsCollection) - коллекция моделей создаваемой сущности
    2. Результатом выполнения будет новая коллекция созданных сущностей
    ```php
    addComplex(LeadsCollection $collection);
    ```
2. addOneComplex Создать одну сделку со связанным контакт и компанией через [комплексный метод](https://www.amocrm.ru/developers/content/crm_platform/leads-api#leads-complex-add) с поддержкой [контроля дублей](https://www.amocrm.ru/developers/content/crm_platform/duplication-control)
    1. collection (LeadsCollection) - коллекция моделей создаваемой сущности
    2. Результатом выполнения будет новая модель созданной сделки
    ```php
    addOneComplex(LeadModel $model);
    ```

Подробнее про использование метода комплексного создания смотрите в [примере](examples/leads_complex_actions.php)

#### Методы доступные в сервисе ```getOAuthClient```:
1. getAuthorizeUrl получение ссылки на авторизация
    1. options (array)
        1. state (string) состояние приложения
    2. Результатом выполнения будет строка со ссылкой на авторизация приложения
    ```php
    getAuthorizeUrl(array $options = []);
    ```
   
2. getAccessTokenByCode получение access токена по коду авторизации
    1. code (string) код авторизации
    2. Результатом выполнения будет объект (AccessTokenInterface)
    ```php
    getAccessTokenByCode(string $code);
    ```
   
3. getAccessTokenByRefreshToken получение access токена по refresh токену
    1. accessToken (AccessTokenInterface) объект access токена
    2. Результатом выполнения будет объект (AccessTokenInterface)
    ```php
    getAccessTokenByRefreshToken(AccessTokenInterface $accessToken);
    ```
   
4. setBaseDomain установка базового домена, куда будут отправляться запросы необходимые для работы с токенами
    1. domain (string)
    ```php
    setBaseDomain(string $domain);
    ```

5. setAccessTokenRefreshCallback установка callback, который будет вызван при обновлении access токена
    1. function (callable)
    ```php
    setAccessTokenRefreshCallback(callable $function);
    ```

6. getOAuthButton установка callback, который будет вызван при обновлении access токена
    1. options (array)
        1. state (string) состояние приложения
        2. color (string)
        3. title (string)
        4. compact (bool)
        5. class_name (string)
        6. error_callback (string)
        7. mode (string)
    2. Результатом выполнения будет строка с HTML кодом кнопки авторизации
    ```php
    getOAuthButton(array $options = []);
    ```
   
7. exchangeApiKey метод для обмена API ключа на код авторизации
    1. login - email пользователя, для которого обменивается API ключ
    2. apiKey - API ключ пользователя
    3. Код авторизации будет прислан на указанный в настройках приложения redirect_uri
    ```php
    exchangeApiKey(string $login, string $apiKey);
    ```

#### Методы связей доступны в сервисах ```leads```, ```contacts```, ```companies```, ```customers```:

1. link Привязать сущность
    1. model (BaseApiModel) - модель главной сущности
    2. links (LinksCollection|LinkModel) - коллекция или модель связи
    3. Результатом выполнения является коллекция связей (LinksCollection)
    ```php
    link(BaseApiModel $model, $linkedEntities);
    ```

2. getLinks Получить связи сущности
    1. model (BaseApiModel) - модель главной сущности
    2. filter (LinksFilter) - фильтр для связей
    3. Результатом выполнения является коллекция связей (LinksCollection)
    ```php
    getLinks(BaseApiModel $model, LinksFilter $filter);
    ```
       
3. unlink Отвязать сущность
    1. model (BaseApiModel) - модель главной сущности
    2. links (LinksCollection|LinkModel) - коллекция или модель связи
    3. Результатом выполнения является bool значение
    ```php
    unlink(BaseApiModel $model, $linkedEntities);
    ```

#### Методы удаления доступны в сервисах ```transactions```, ```lossReasons```, ```statuses```, ```pipelines```, ```customFields```, ```customFieldsGroups```, ```roles```, ```customersStatuses```:

1. delete
    1. model (BaseApiModel) - модель сущности
    2. Результатом выполнения является bool значение
    ```php
    deleteOne(BaseApiModel $model);
    ```
   
2. deleteOne
    1. collection (BaseApiCollection) - коллекция моделей сущностей
    2. Результатом выполнения является bool значение
    ```php
    deleteOne(BaseApiModel $model);
    ```


#### Методы доступные в сервисе ```customers```:
1. setMode Смена режима покупателей (периодические покупки или сегментация). Если покупатели выключены - то они будут включены.
    1. mode (string) - тип режима (periodicity или segments)
    1. isEnabled (bool) - включен ли функционал покупателей, по-умолчанию - true
    2. Результатом выполнения является строка названия включенного режима или null в случае отключения функционала
    ```php
    setMode(string $mode, bool $isEnabled = true);
    ```

#### Методы доступные в сервисе ```customersBonusPoints```:
1. earnPoints Начисляет бонусные баллы покупателю
    1. model (BonusPointsActionModel) - модель в которой Id покупателя и количество баллов для начисления
    2. Результатом выполнения является обновленное количество бонусных баллов покупателя или null в случае если произошла ошибка
    ```php
    earnPoints(BonusPointsActionModel $bonusPointsActionModel)
    ```

2. redeemPoints Списывает бонусные баллы покупателя
    1. model (BonusPointsActionModel) - модель в которой Id покупателя и количество баллов для списания
    2. Результатом выполнения является обновленное количество бонусных баллов покупателя или null в случае если произошла ошибка
    ```php
    redeemPoints(BonusPointsActionModel $bonusPointsActionModel)
    ```

#### Методы доступные в сервисе ```notes```, ```entitySubscriptions```:
1. getByParentId Получение данных по ID родительской сущности
    1. parentId - ID родительской сущности
    2. filter (BaseEntityFilter) - фильтр
    3. with (array) - массив параметров with, которые поддерживает модель сервиса
    ```php
   getByParentId(int $parentId, BaseEntityFilter $filter = null, array $with = []);
    ```


#### Методы доступные в сервисе ```account```
1. getCurrent
    1. with (array) - массив параметров with, которые поддерживает модель сервиса
    2. Результатом выполнения является модель AccountModel
    ```php
    getCurrent(array $with = []);
    ```
   

#### Методы доступные в сервисе ```unsorted```
1. addOne Создать одну сущность:
    1. model (BaseApiModel) - модель создаваемой сущности
    2. Результатом выполнения будет модель сущности
    ```php
    addOne(BaseApiModel $model);
    ```
   
2. add Создать сущности пакетно:
    1. collection (BaseApiCollection) - коллекция моделей создаваемой сущности
    2. Результатом выполнения будет коллекция моделей сущности
    ```php
    add(BaseApiCollection $collection);
    ```

3. link
    1. model (BaseApiModel) - модель неразобранного
    2. body (array) - массив дополнительной информации для привязки 
    2. Результатом выполнения будет модель LinkUnsortedModel
    ```php
    link(BaseApiModel $unsortedModel, $body = []);
    ```
   
4. accept
    1. model (BaseApiModel) - модель неразобранного
    2. body (array) - массив дополнительной информации для принятия 
    2. Результатом выполнения будет модель AcceptUnsortedModel
    ```php
    accept(BaseApiModel $unsortedModel, $body = []);
    ```

5. decline
    1. model (BaseApiModel) - модель неразобранного
    2. body (array) - массив дополнительной информации для отклонения 
    2. Результатом выполнения будет модель DeclineUnsortedModel
    ```php
    decline(BaseApiModel $unsortedModel, $body = []);
    ```

6. summary
    1. filter (BaseEntityFilter) - фильтр для сущности
    2. Результатом выполнения будет модель UnsortedSummaryModel
    ```php
    summary(BaseEntityFilter $filter);
    ```
   

#### Методы доступные в сервисе ```webhooks```

1. subscribe
    1. model (WebhookModel) - модель вебхука
    2. Результатом выполнения является модель WebhookModel
    ```php
    subscribe(WebhookModel $webhookModel);
    ```
   
2. unsubscribe
    1. model (WebhookModel) - модель вебхука
    2. Результатом выполнения является bool значение
    ```php
    unsubscribe(WebhookModel $webhookModel);
    ```


#### Методы доступные в сервисе ```widgets```

1. install
    1. model (WidgetModel) - модель виджета
    2. Результатом выполнения является модель WidgetModel
    ```php
    install(WidgetModel $widgetModel);
    ```
   
2. uninstall
    1. model (WidgetModel) - модель виджета
    2. Результатом выполнения является модель WidgetModel
    ```php
    uninstall(WidgetModel $widgetModel);
    ```
 
#### Методы доступные в сервисе ```products```

1. settings
    1. Результатом выполнения является модель ProductsSettingsModel
    ```php
    settings();
    ```
   
2. updateSettings
    1. model (ProductsSettingsModel) - модель виджета
    2. Результатом выполнения является модель ProductsSettingsModel
    ```php
    updateSettings(ProductsSettingsModel $productsSettings);
    ```

#### Методы, доступные в сервисе ```talks```

1. close
   1. model (TalkCloseActionModel) - модель для закрытия беседы
   2. Результатом выполнения - является закрытие беседы или запуск NPS-бота для последующего закрытия беседы
    ```php
    close(TalkCloseActionModel $closeAction)
    ```

## Обработка ошибок

Вызов методов библиотеки может выбрасывать ошибки типа ```AmoCRMApiException```.
В данные момент доступны следующие типы ошибок, они все наследуют AmoCRMApiException:

|Тип                                                 |Условия                                                                                               |
|----------------------------------------------------|------------------------------------------------------------------------------------------------------|
|AmoCRM\Exceptions\AmoCRMApiConnectExceptionException|Подключение к серверу не было выполнено                                                               |
|AmoCRM\Exceptions\AmoCRMApiErrorResponseException   |Сервер вернул ошибку на выполняемый запрос                                                            |
|AmoCRM\Exceptions\AmoCRMApiHttpClientException      |Произошла ошибка http клиента                                                                         |
|AmoCRM\Exceptions\AmoCRMApiNoContentException       |Сервер вернул код 204 без результата, ничего страшного не произошло, просто нет данных на ваш запрос  |
|AmoCRM\Exceptions\AmoCRMApiTooManyRedirectsException|Слишком много редиректов (в нормальном режиме не выкидывается)                                        |
|AmoCRM\Exceptions\AmoCRMoAuthApiException           |Ошибка в oAuth клиенте                                                                                |
|AmoCRM\Exceptions\BadTypeException                  |Передан неверный тип данных                                                                          |
|AmoCRM\Exceptions\InvalidArgumentException          |Передан неверный аргумент                                                                            |
|AmoCRM\Exceptions\NotAvailableForActionException    |Метод не доступен для вызова                                                                          |
|AmoCRM\Exceptions\AmoCRMApiPageNotAvailableException|Выбрасывается в случае запроса следующей или предыдущей страницы коллекции, когда страница отсутствует|
|AmoCRM\Exceptions\AmoCRMMissedTokenException        |Не установлен Access Token для выполнения запроса                                                     |

У выброшенных Exception есть следующие методы:
1. ```getErrorCode()```
2. ```getTitle()```
3. ```getLastRequestInfo()```
4. ```getDescription()```

У ошибки типа AmoCRMApiErrorResponseException есть метод ```getValidationErrors()```, который вернет ошибки валидации входных данных.

## Фильтры

В данный момент библиотека поддерживает фильтры для следующих сервисов:

|Сервис                                                       |Фильтр                                     |Особенности                                                                                       |Поддерживает ли сортировку? |
|-------------------------------------------------------------|-------------------------------------------|--------------------------------------------------------------------------------------------------|----------------------------|
|```catalogElements```                                        |```\AmoCRM\Filters\CatalogElementsFilter```|Доступен в ограниченном виде, в будущих версиях будет расширен                                    |❌                          |
|```companies```                                              |```\AmoCRM\Filters\CompaniesFilter```      |Доступен только на аккаунтах, которые подключены к тестированию функционала фильтрации по API     |✅                          |
|```contacts```                                               |```\AmoCRM\Filters\ContactsFilter```       |Доступен только на аккаунтах, которые подключены к тестированию функционала фильтрации по API     |✅                          |
|```customers```                                              |```\AmoCRM\Filters\CustomersFilter```      |Доступен только на аккаунтах, которые подключены к тестированию функционала фильтрации по API     |✅                          |
|```leads```                                                  |```\AmoCRM\Filters\LeadsFilter```          |Доступен только на аккаунтах, которые подключены к тестированию функционала фильтрации по API     |✅                          |
|```events```                                                 |```\AmoCRM\Filters\EventsFilter```         |Фильтр для списка событий                                                                         |❌                          |
|```leads```, ```contacts```, ```customers```, ```companies```|```\AmoCRM\Filters\LinksFilter```          |Фильтр для получения связей для метода `\AmoCRM\EntitiesServices\HasLinkMethodInterface::getLinks`|❌                          |
|```notes```                                                  |```\AmoCRM\Filters\NotesFilter```          |Фильтра для `\AmoCRM\EntitiesServices\EntityNotes::get`                                           |✅                          |
|```tags```                                                   |```\AmoCRM\Filters\TagsFilter```           |Фильтр для `\AmoCRM\EntitiesServices\EntityTags::get`                                             |❌                          |
|```tasks```                                                  |```\AmoCRM\Filters\TasksFilter```          |Фильтр для метода `\AmoCRM\EntitiesServices\Tasks::get`                                           |✅                          |
|```unsorted```                                               |```\AmoCRM\Filters\UnsortedFilter```       |Фильтр для метода `\AmoCRM\EntitiesServices\Unsorted::get`                                        |✅                          |
|```unsorted```                                               |```\AmoCRM\Filters\UnsortedSummaryFilter```|Фильтр для метода `\AmoCRM\EntitiesServices\Unsorted::summary`                                    |❌                          |
|```webhooks```                                               |```\AmoCRM\Filters\WebhooksFilter```       |Фильтр для метода получения хуков                                                                 |❌                          |
|```sources```                                                |```\AmoCRM\Filters\SourcesFilter```        |Фильтр для метода получения источников `\AmoCRM\EntitiesServices\Sources::get`                    |❌                          |
|```chatTemplates```                                          |```\AmoCRM\Filters\Chats\TemplatesFilter```|Фильтр для метода получения шаблонов чатов `\AmoCRM\EntitiesServices\Chats\Templates::get`        |❌                          |
|Сервисы, где необходима постраничная навигация               |```\AmoCRM\Filters\PagesFilter```          |Фильтр, который подходит для любого сервиса, где есть постраничная навигация                      |❌                          |


## Работа с дополнительными полями сущностей

Дополнительные поля доступны у сущностей следующих сервисов:
1. ```leads```
2. ```contacts```
3. ```companies```
4. ```customers```
5. ```catalogElements```
6. ```segments```

У моделей, которые возвращаются этими сервисами, поля можно получить через метод ```getCustomFieldsValues()```.
На вызов данного метода возвращается объект ```CustomFieldsValuesCollection``` или ```null```, 
если значений полей нет.

Внутри коллекции ```CustomFieldsValuesCollection``` находятся модели значений полей, 
все модели наследуются от ```BaseCustomFieldValuesModel```, но зависят от типа поля.

У моделей, наследующих ```BaseCustomFieldValuesModel``` доступны следующие методы:
1. ```getFieldId```, ```setFieldId``` - получение/установка id поля
2. ```getFieldType``` - получение типа поля
3. ```getFieldCode```, ```setFieldCode``` - получение/установка кода поля
4. ```getFieldName```, ```setFieldName``` - получение/установка названия поля
5. ```getValues```, ```setValues``` - получение/установка коллекции значений

Так как некоторые поля могут иметь несколько значений,
в свойстве values хранится именно коллекция значений типа ```BaseCustomFieldValueCollection```.
Моделями коллекции являются модели типа ```BaseCustomFieldValueModel```.

#### Схема отношений объектов:

```CustomFieldsValuesCollection 1 <---> n BaseCustomFieldValuesModel```

```BaseCustomFieldValuesModel::getValues() 1 <---> 1 BaseCustomFieldValueCollection```

```BaseCustomFieldValueCollection 1 <---> n BaseCustomFieldValueModel```

#### Для разных типов полей мы уже подготовили разные модели и коллекции:

Namespace, в котором находятся модели значения - ```\AmoCRM\Models\CustomFieldsValues\ValueModels```

Namespace, в котором находятся коллекции моделей значения - ```\AmoCRM\Models\CustomFieldsValues\ValueCollections```

Namespace, в котором находятся модели дополнительных полей - ```\AmoCRM\Models\CustomFieldsValues```

| Тип поля                 | Модель значения                    | Коллекция моделей значений              | Модель доп поля                     | Контакт | Сделка | Компания | Покупатель | Каталог | Сегмент |
|--------------------------|------------------------------------|-----------------------------------------|-------------------------------------|:-------:|:------:|:--------:|:----------:|:-------:|:-------:|
| Текст                    | TextCustomFieldValueModel          | TextCustomFieldValueCollection          | TextCustomFieldValuesModel          |    ✅    |   ✅    |    ✅     |     ✅      |    ✅    |    ✅    |
| Число                    | NumericCustomFieldValueModel       | NumericCustomFieldValueCollection       | NumericCustomFieldValuesModel       |    ✅    |   ✅    |    ✅     |     ✅      |    ✅    |    ✅    |
| Флаг                     | CheckboxCustomFieldValueModel      | CheckboxCustomFieldValueCollection      | CheckboxCustomFieldValuesModel      |    ✅    |   ✅    |    ✅     |     ✅      |    ✅    |    ✅    |
| Список                   | SelectCustomFieldValueModel        | SelectCustomFieldValueCollection        | SelectCustomFieldValuesModel        |    ✅    |   ✅    |    ✅     |     ✅      |    ✅    |    ✅    |
| Мультисписок             | MultiselectCustomFieldValueModel   | MultiselectCustomFieldValueCollection   | MultiSelectCustomFieldValuesModel   |    ✅    |   ✅    |    ✅     |     ✅      |    ✅    |    ✅    |
| Мультитекст              | MultitextCustomFieldValueModel     | MultitextCustomFieldValueCollection     | MultitextCustomFieldValuesModel     |    ✅    |   ❌    |    ❌     |     ❌      |    ❌    |    ❌    |
| Дата                     | DateCustomFieldValueModel          | DateCustomFieldValueCollection          | DateCustomFieldValuesModel          |    ✅    |   ✅    |    ✅     |     ✅      |    ✅    |    ✅    |
| Ссылка                   | UrlCustomFieldValueModel           | UrlCustomFieldValueCollection           | UrlCustomFieldValuesModel           |    ✅    |   ✅    |    ✅     |     ✅      |    ✅    |    ✅    |
| Дата и время             | DateTimeCustomFieldValueModel      | DateTimeCustomFieldValueCollection      | DateTimeCustomFieldValuesModel      |    ✅    |   ✅    |    ✅     |     ✅      |    ✅    |    ✅    |
| Текстовая область        | TextareaCustomFieldValueModel      | TextareaCustomFieldValueCollection      | TextareaCustomFieldValuesModel      |    ✅    |   ✅    |    ✅     |     ✅      |    ✅    |    ✅    |
| Переключатель            | RadiobuttonCustomFieldValueModel   | RadiobuttonCustomFieldValueCollection   | RadiobuttonCustomFieldValuesModel   |    ✅    |   ✅    |    ✅     |     ✅      |    ✅    |    ✅    |
| Короткий адрес           | StreetAddressCustomFieldValueModel | StreetAddressCustomFieldValueCollection | StreetAddressCustomFieldValuesModel |    ✅    |   ✅    |    ✅     |     ✅      |    ✅    |    ✅    |
| Адрес                    | SmartAddressCustomFieldValueModel  | SmartAddressCustomFieldValueCollection  | SmartAddressCustomFieldValuesModel  |    ✅    |   ✅    |    ✅     |     ❌      |    ❌    |    ❌    |
| День рождения            | BirthdayCustomFieldValueModel      | BirthdayCustomFieldValueCollection      | BirthdayCustomFieldValuesModel      |    ✅    |   ✅    |    ✅     |     ❌      |    ❌    |    ❌    |
| Юр. лицо                 | LegalEntityCustomFieldValueModel   | LegalEntityCustomFieldValueCollection   | LegalEntityCustomFieldValuesModel   |    ✅    |   ✅    |    ✅     |     ❌      |    ❌    |    ❌    |
| Цена                     | PriceCustomFieldValueModel         | PriceCustomFieldValueCollection         | PriceCustomFieldValuesModel         |    ❌    |   ❌    |    ❌     |     ❌      |    ✅    |    ❌    |
| Категория                | CategoryCustomFieldValueModel      | CategoryCustomFieldValueCollection      | CategoryCustomFieldValuesModel      |    ❌    |   ❌    |    ❌     |     ❌      |    ✅    |    ❌    |
| Предметы                 | ItemsCustomFieldValueModel         | ItemsCustomFieldValueCollection         | ItemsCustomFieldValuesModel         |    ❌    |   ❌    |    ❌     |     ❌      |    ✅    |    ❌    |
| Метка                    | TrackingDataCustomFieldValueModel  | TrackingDataCustomFieldValueCollection  | TrackingDataCustomFieldValuesModel  |    ❌    |   ✅    |    ❌     |     ❌      |    ❌    |    ❌    |
| Связь с другим элементом | LinkedEntityCustomFieldValueModel  | LinkedEntityCustomFieldValueCollection  | LinkedEntityCustomFieldValuesModel  |    ❌    |   ❌    |    ❌     |     ❌      |    ✅    |    ❌    |
| Денежное                 | MonetaryCustomFieldModel           | MonetaryCustomFieldValueCollection      | MonetaryCustomFieldValuesModel      |    ✅    |   ✅    |    ✅     |     ✅      |    ❌    |    ❌    |
| Каталоги и списки        | ChainedListCustomFieldModel        | ChainedListCustomFieldValueCollection   | ChainedListCustomFieldValuesModel   |    ❌    |   ✅    |    ❌     |     ✅      |    ❌    |    ❌    |
| Файл                     | FileCustomFieldModel               | FileCustomFieldValueCollection          | FileCustomFieldValuesModel          |    ✅    |   ✅    |    ✅     |     ✅      |    ❌    |    ❌    |

Пример кода, как создать коллекцию значения полей сущности:
```php
//Создадим модель сущности
$lead = new LeadModel();
$lead->setId(1);
//Создадим коллекцию полей сущности
$leadCustomFieldsValues = new CustomFieldsValuesCollection();
//Создадим модель значений поля типа текст
$textCustomFieldValuesModel = new TextCustomFieldValuesModel();
//Укажем ID поля
$textCustomFieldValuesModel->setFieldId(123);
//Добавим значения
$textCustomFieldValuesModel->setValues(
    (new TextCustomFieldValueCollection())
        ->add((new TextCustomFieldValueModel())->setValue('Текст'))
);
//Добавим значение в коллекцию полей сущности
$leadCustomFieldsValues->add($textCustomFieldValuesModel);
//Установим в сущности эти поля
$lead->setCustomFieldsValues($leadCustomFieldsValues);
``` 

Чтобы удалить значения поля доступен специальный объект ```\AmoCRM\Models\CustomFieldsValues\ValueCollections\NullCustomFieldValueCollection```.

Передав этот объект, вы зануляете значение поля.

Пример:
```php
//Создадим модель сущности
$lead = new LeadModel();
$lead->setId(1);
//Создадим коллекцию полей сущности
$leadCustomFieldsValues = new CustomFieldsValuesCollection();
//Создадим модель значений поля типа текст
$textCustomFieldValuesModel = new TextCustomFieldValuesModel();
//Укажем ID поля
$textCustomFieldValuesModel->setFieldId(123);
//Обнулим значения
$textCustomFieldValuesModel->setValues(
    (new NullCustomFieldValueCollection())
);
//Добавим значение в коллекцию полей сущности
$leadCustomFieldsValues->add($textCustomFieldValuesModel);
//Установим сущности эти поля
$lead->setCustomFieldsValues($leadCustomFieldsValues);
```

## Работа с тегами сущностей

Теги доступны как отдельный сервис ```tags```.
При создании данного сервиса, вы указываете тип сущности, с тегами которой вы будете работать.

В данный момент доступны:
1. EntityTypesInterface::LEADS,
2. EntityTypesInterface::CONTACTS,
3. EntityTypesInterface::COMPANIES,
4. EntityTypesInterface::CUSTOMERS,

Для работы с тегами конкретной сущности, нужно взаимодействовать с конкретной моделью сущности.
С помощью методов ```getTags``` и ```setTags``` вы можете получить коллекцию тегов сущности или установить её.

Для изменения тегов вам необходимо передавать всю коллекцию тегов, иначе теги могут быть потеряны.

Пример добавления/изменения тегов у сущности:
```php
//Создадим модель сущности
$lead = new LeadModel();
$lead->setId(1);
//Создадим коллекцию тегов с тегами и установим их в сущности
$lead->setTags((new TagsCollection())
    ->add(
        (new TagModel())
            ->setName('тег')
    )->add(
        (new TagModel())
            ->setId(123123)
    )
);
```

или

```php
//Создадим модель сущности
$lead = new LeadModel();
$lead->setId(1);
//Создадим коллекцию тегов с тегами и установим их в сущности
$lead->setTags(
    TagsCollection::fromArray([
        [
            'name' => 'тег',
        ],
        [
            'id' => 123,
        ],
    ])
);
```

Для удаления тегов в setTags можно передать в ```setTags``` специальный объект ```\AmoCRM\Collections\NullTagsCollection```.

Пример удаления тегов у сущности:
```php
//Создадим модель сущности
$lead = new LeadModel();
$lead->setId(1);
//Удалим теги
$lead->setTags((new NullTagsCollection()));
```

## Особенности работы с источниками

На данный момент источники созданные интеграциями учитываются только при создании неразобранного из чатов.

При добавлении источника обязательными полями являются `external_id`, `name` интеграция может создать в аккаунте
не более 50 активных источников на аккаунт. При удалении источника, к примеру, со значением `external_id: 'sales'`
и при повторном создании с тем же `external_id` crm может вернуть `id` раннее удаленного источника. Поэтому не стоит 
на стороне интеграции формировать первичный ключ из поля `id`.

Чтобы источник отображался в кнопке whatsapp CRM Plugin необходимо указать поле источника `services` с таким содержимым:
```json
 [
   {
      "type": "whatsapp",
      "pages": [
         {
            "id": "<идентификатор или номер телефона>",
            "name": "My WhatsApp",
            "link": "<номер телефона>"
         }
      ]
   }
]

```
Чтобы правильно сформировать поле `services` можно воспользоваться моделью ```\AmoCRM\Collections\Sources\SourceServicesCollection```

### Источник по-умолчанию

Источник по-умолчанию (с полем `default=true`) может быть только один или отсутствовать совсем. При отсутствии источника
по-умолчанию в сделках будет указан источник АПИ-интеграция с названием интеграции (как при создании неразобранного через АПИ).

Чтобы сменить источник по-умолчанию, достаточно нужному источнику проставить поле `default=true`, у предыдущего источника
поле default будет выставлено в `default=false`. Но при удалении источника по-умолчанию интеграция сама должна указать 
новый источник по-умолчанию.

### Миграция интеграции на множественные источники (для интеграций с чатами)

Источник по-умолчанию может быть использован интеграцией при переходе на множественные источники, особенно если
интеграция поддерживает опцию написать первым. 

К примеру исходное состояние:  
   Есть аккаунт с подключенной интеграцией с чатами, эта интеграция поддерживает только 1 источник. 
   На данный момент нам не важно как была установлена интеграция: через DP или маркетплейс.

Интеграция начинает внедрение поддержки множественных источников, логически разобьем на этапы:  

**1 этап**   
Интеграция умеет работать с АПИ источниками (но не отправляет и не принимает источник в сообщениях amojo). 
Добавляет источник по-умолчанию, который логически соответствует источнику, использовавшемуся когда не было поддержки нескольких источников. Теперь crm будет присылать в сообщениях `external_id` этого источника для всех чатов которые явно 
не закреплены за конкретным источником.

**2 этап**  
Интеграция умеет работать с источниками и при отправке сообщений от клиента (при создании чата) указывает `external_id`
Все чаты с новыми сообщениями становятся размеченными по источнику. 

Так же интеграция теперь обрабатывает источник и учитывает его при отправке сообщения от менеджера, в том числе при начале чата с клиентом (опция "написать первым").

**3 этап**  
Интеграция позволяет администратору аккаунта добавить (через интеграцию) второй и последующие источники.
Вся переписка числится за каким-то источником

**Важный момент**
Источник по-умолчанию не привязывается к чатам, если его явно не передавали с сообщениями и тогда при смене 
источника по-умолчанию чат без разметки будет "числиться" за новым источником

## Константы

Основные константы находятся в интерфейсе ```\AmoCRM\Helpers\EntityTypesInterface```.

Также доступны константы в следующих классах/интерфейсах:
1. ```\AmoCRM\OAuth\AmoCRMOAuth::BUTTON_COLORS``` - доступные цвета для кнопки на сайт
2. ```\AmoCRM\Models\Unsorted\BaseUnsortedModel``` - константы для кодов категорий неразобранного
3. ```\AmoCRM\Models\CustomFields\BirthdayCustomFieldModel``` - константы для свойства remind у поля День Рождения
4. ```\AmoCRM\Models\Interfaces\CallInterface``` - константы статусов звонков
5. ```\AmoCRM\EntitiesServices\Interfaces\HasParentEntity``` - константы для ключей в запросах методов, у которых есть родительский сущность (в данный момент только notes)
6. ```\AmoCRM\Models\CustomFieldsValues\ValueModels\ItemsCustomFieldValueModel``` - константы для ключей значения поля Items
7. ```\AmoCRM\Models\Rights\RightModel``` - константы, связанные с правами
8. ```\AmoCRM\Models\AccountModel``` - константы для аргумента with для сервиса ```account```
9. ```\AmoCRM\Models\TaskModel``` - константы для дефолтных типов задач
10. ```\AmoCRM\Models\NoteType\TargetingNote``` - константы поддерживаемых внешних сервисов для примечаний о таргетинге (добавляют DP) 
11. ```\AmoCRM\Models\RoleModel``` - константы для аргумента with для сервиса ```roles```
12. ```\AmoCRM\Models\Factories\NoteFactory``` - константы типов примечаний
13. ```\AmoCRM\Models\NoteType\MessageCashierNote``` - статусы примечания "Сообщение кассиру"
14. ```\AmoCRM\Models\LeadModel``` - константы для аргумента with для сервиса ```leads```
15. ```\AmoCRM\Filters\Interfaces\HasOrderInterface``` - константы для сортировки
16. ```\AmoCRM\Models\EventModel``` - константы для аргумента with для сервиса ```events```
17. ```\AmoCRM\Models\CustomFields\CustomFieldModel``` - константы типов полей
18. ```\AmoCRM\Models\Customers\CustomerModel``` - константы для аргумента with для сервиса ```customers```
19. ```\AmoCRM\Models\ContactModel``` - константы для аргумента with для сервиса ```contacts```
20. ```\AmoCRM\Models\CompanyModel``` - константы для аргумента with для сервиса ```companies```
21. ```\AmoCRM\Models\CatalogElementModel``` - константы для аргумента with для сервиса ```catalogElements```
22. ```\AmoCRM\Enum\InvoicesCustomFieldsEnums``` - константы для работы с полями каталога счетов (с версии 0.12 константы статусов переехали в \AmoCRM\Enum\Invoices\BillStatusEnumCode)
23. ```\AmoCRM\Enum\Chats\Templates\Buttons\ButtonsEnums``` - типы кнопок шаблонов чатов
24. ```\AmoCRM\Enum\Sources\SourceServiceTypeEnum``` - типы сервисов для источников
25. ```\AmoCRM\Enum\Tags\TagColorsEnum``` - возможные цвета для тегов
26. ```\AmoCRM\Enum\Invoices\BillStatusEnumCode``` - предустановленные статусы для Счетов/Покупок

## Работа в случае смены субдомена аккаунта

```php
/**
 * Получим модель с информацией о домене аккаунта по access_token
 * Подробнее: @see AccountDomainModel
 *
 * Запрос уходит на www.amocrm.ru/oauth2/account/subdomain
 * С Authorization: Bearer {access_token}
 * curl 'https://www.amocrm.ru/oauth2/account/subdomain' -H 'Authorization: Bearer {access_token}'
 *
 * @example examples/get_account_subdomain.php
 */
$accountDomain = $apiClient->getOAuthClient()
        ->getAccountDomain($accessToken);

// Возьмём из полученной модели текущий subdomain аккаунта и засетим наш апи клиент
$apiClient->setAccountBaseDomain($accountDomain->getSubdomain());
// ... дальше продолжаем работу с апи клиентом
```

## Одноразовые токены интеграций, расшифровка
```php
// Как пример, получим заголовки с реквеста
// И получим нужный нам X-Auth-Token
$token = $_SERVER['HTTP_X_AUTH_TOKEN'];

try {
    /**
     * Одноразовый токен для интеграций, для того чтобы его получить используйте
     * метод this.$authorizedAjax() в своей интеграции
     * Подробнее: @link https://www.amocrm.ru/developers/content/web_sdk/mechanics
     *
     * Данный токен должен передаваться в заголовках вместе с запросом на ваш удаленный сервер
     * X-Auth-Token: {disposable_token}
     * Время жизни токена: 30 минут
     *
     * Расшифруем пришедший токен и получим модель с информацией
     * Подробнее: @see DisposableTokenModel
     */
    $disposableTokenModel = $apiClient->getOAuthClient()
        ->parseDisposableToken($token);

    var_dump($disposableTokenModel->toArray());
} catch (DisposableTokenExpiredException $e) {
    // Время жизни токена истекло
    printError($e);
    die;
} catch (DisposableTokenInvalidDestinationException $e) {
    // Не прошёл проверку на адресата токена
    printError($e);
    die;
} catch (DisposableTokenVerificationFailedException $e) {
    // Токен не прошел проверку подписи
    printError($e);
    die;
}
```
Также вы можете распарсить и модель одноразового токена для Salesbot/Marketingbot.
Для этого необходимо сделать вызов метода `parseBotDisposableToken`:
```php
$token = 'XXX';
try {
    /**
     * Одноразовый токен для ботов, его вы можете получить, сделав вызов widget_request в виджете в боте
     * Подробнее: @link https://www.amocrm.ru/developers/content/digital_pipeline/salesbot#handler-widget_request
     *
     * Данный токен содержит в себе информацию об аккаунте и о сущности, с которой работает бот
     * Для продолжения бота необходимо сделать запрос на метод, который был получен в теле хука
     * Подробнее: @link https://www.amocrm.ru/developers/content/crm_platform/widgets-api#widget-continue  
     *
     * Расшифруем пришедший токен и получим модель с информацией
     * Подробнее: @see BotDisposableTokenModel
     */
    $botDisposableTokenModel = $apiClient->getOAuthClient()
        ->parseBotDisposableToken($token);

    var_dump($botDisposableTokenModel->toArray());
} catch (DisposableTokenExpiredException $e) {
    // Время жизни токена истекло
    printError($e);
    die;
} catch (DisposableTokenInvalidDestinationException $e) {
    // Не прошёл проверку на адресата токена
    printError($e);
    die;
} catch (DisposableTokenVerificationFailedException $e) {
    // Токен не прошел проверку подписи
    printError($e);
    die;
}
```

## Работа с валютами

```php
/** @var AmoCRMApiClient $apiClient */

# Получим сервис для работы с валютами
$service = $apiClient->currencies();

# Получение списка валют
try {
    $collection = $service->get();
    var_dump($collection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}

# Получение списка валют с фильтром
$filter = new CurrenciesFilter();
$filter->setLimit(50);
$filter->setPage(2);

try {
    $collection = $service->get($filter);
    var_dump($collection);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
```

## Примеры

В рамках данного репозитория имеется папка examples с различными примерами.

Для их работы необходимо добавить в неё файл .env со следующим содержимым, указав ваши значения:
```dotenv
CLIENT_ID="UUID интеграци"
CLIENT_SECRET="Секретный ключ интеграции"
CLIENT_REDIRECT_URI="https://example.com/examples/get_token.php (Важно обратить внимание, что он должен содержать в себе точно тот адрес, который был указан при создании интеграции)"
```

Затем вы можете поднять локальный сервер командой ```composer serve```. После конфигурации необходимо перейти в браузере на страницу
```http://localhost:8181/examples/get_token.php``` для получения Access Token.
Для получения доступа к вашему локальному серверу извне можно использовать сервис ngrok.io. 

После авторизации вы можете проверить работу примеров, обращаясь к ним из браузера. Стоит отметить, что для корректной работы примеров
необходимо проверить ID сущностей в них.

## Работа с Issues
Если вы столкнулись с проблемой при работе с библиотекой, вы можете составить Issue, который будет рассмотрен при первой возможности.

При составлении, детально опишите проблему, приложите примеры кода, а также ответы на запросы из `getLastRequestInfo`.

Не забывайте удалять из примеров значимые данные, которые не должны стать достоянием общественности.

Также могут быть рассмотрены пожелания по улучшению библиотеки.

Вы можете предложить свои исправления/изменения исходного кода библиотеки, посредством создания Issue с описанием, а также Pull request с упоминанием Issue в комментарии к нему. 
Они будут рассмотрены и будут приняты или отклонены. Некоторые Pull Request могут остаться без ответа и действия, в случае, если правки потенциально жизнеспособны, но в данный момент не являются ключевыми для проекта.

Если вы столкнулись с проблемой функционала amoCRM - обратитесь в техническую поддержку через чат в вашем аккаунте.

## License

MIT
