<?php

namespace Cases\AmoCRM\OAuth;

use AmoCRM\Exceptions\AmoCRMOAuthButtonConfigurationException;
use PHPUnit\Framework\TestCase;
use AmoCRM\OAuth\AmoCRMOAuth;

class AmoCRMOAuthButtonTest extends TestCase
{
    /**
     * @var AmoCRMOAuth
     */
    private $oauthClient;


    /**
     * Создаем клиент с client_id для тестирования обычной кнопки
     */
    protected function setUp(): void
    {
        $this->oauthClient = new AmoCRMOAuth(
            'test_client_id',
            'test_secret',
            'https://example.com/redirect'
        );
    }

    /**
     * Проверяем генерацию обычной кнопки с client_id
     * @throws AmoCRMOAuthButtonConfigurationException
     */
    public function testRegularButtonWithClientId(): void
    {
        $result = $this->oauthClient->getOAuthButton([]);
        $this->assertStringContainsString('data-client-id="test_client_id"', $result);
        $this->assertStringContainsString('data-title="Установить интеграцию"', $result);
        $this->assertStringContainsString('data-class-name="className"', $result);
    }


    /**
     * Проверяем кастомные параметры для обычной кнопки
     * @throws AmoCRMOAuthButtonConfigurationException
     */
    public function testRegularButtonWithCustomOptions(): void
    {
        $result = $this->oauthClient->getOAuthButton([
            'title' => 'Custom Title',
            'class_name' => 'custom-class',
            'color' => 'red',
            'compact' => false,
            'mode' => 'popup'
        ]);

        $this->assertStringContainsString('data-title="Custom Title"', $result);
        $this->assertStringContainsString('data-class-name="custom-class"', $result);
        $this->assertStringContainsString('data-color="red"', $result);
        $this->assertStringContainsString('data-compact="false"', $result);
        $this->assertStringContainsString('data-mode="popup"', $result);
    }

    /**
     * Проверяем ошибку при невалидном цвете
     * @return void
     * @throws AmoCRMOAuthButtonConfigurationException
     */
    public function testInvalidColorThrowsException(): void
    {
        $this->expectException(AmoCRMOAuthButtonConfigurationException::class);
        $this->expectExceptionMessage('Invalid color selected');
        $this->oauthClient->getOAuthButton(['color' => 'invalid_color']);
    }


    /**
     * Тест 4: Проверяем генерацию metadata кнопки
     * @throws AmoCRMOAuthButtonConfigurationException
     */
    public function testMetadataButtonGeneration(): void
    {
        $result = $this->oauthClient->getOAuthButton([
            'is_metadata' => true,
            'name' => 'Test Integration',
            'description' => 'Test Description',
            'scopes' => ['crm', 'notifications'],
            'secrets_uri' => 'https://secrets.com'
        ]);

        $this->assertStringNotContainsString('data-client-id', $result);
        $this->assertStringContainsString('data-name="Test Integration"', $result);
        $this->assertStringContainsString('data-description="Test Description"', $result);
        $this->assertStringContainsString('data-scopes="crm,notifications"', $result);
        $this->assertStringContainsString('data-secrets_uri="https://secrets.com"', $result);
    }

    /**
     * Должно упасть, если не передали redirect_uri
     * @return void
     * @throws AmoCRMOAuthButtonConfigurationException
     */
    public function testMetadataButtonWithoutNameThrowsException(): void
    {
        $oauthClient = new AmoCRMOAuth(
            'test_client_id',
            'test_secret',
            null
        );

        $this->expectException(AmoCRMOAuthButtonConfigurationException::class);


        $oauthClient->getOAuthButton([
            'is_metadata' => true,
            'description' => 'Test',
            'scopes' => ['crm'],
            'secrets_uri' => 'https://secrets.com'
        ]);
    }

    /**
     * Проверяем ошибку при невалидных scopes
     * @return void
     * @throws AmoCRMOAuthButtonConfigurationException
     */
    public function testMetadataButtonWithInvalidScopesThrowsException(): void
    {
        $this->expectException(AmoCRMOAuthButtonConfigurationException::class);
        $this->expectExceptionMessage('Invalid scopes');

        $this->oauthClient->getOAuthButton([
            'is_metadata' => true,
            'name' => 'Test',
            'description' => 'Test',
            'scopes' => ['invalid_scope', 'another_invalid'],
            'secrets_uri' => 'https://secrets.com'
        ]);
    }

    /**
     * Проверяем ошибку когда scopes не массив
     * @return void
     * @throws AmoCRMOAuthButtonConfigurationException
     */
    public function testMetadataButtonWithStringScopesThrowsException(): void
    {
        $this->expectException(AmoCRMOAuthButtonConfigurationException::class);
        $this->expectExceptionMessage('scopes parameter must be an array');

        $this->oauthClient->getOAuthButton([
            'is_metadata' => true,
            'name' => 'Test',
            'description' => 'Test',
            'scopes' => 'crm,notifications',
            'secrets_uri' => 'https://secrets.com'
        ]);
    }

    /**
     * Проверяем использование дефолтных scopes когда не переданы
     * @throws AmoCRMOAuthButtonConfigurationException
     */
    public function testMetadataButtonUsesDefaultScopes(): void
    {
        $result = $this->oauthClient->getOAuthButton([
            'is_metadata' => true,
            'name' => 'Test',
            'description' => 'Test',
            'secrets_uri' => 'https://secrets.com'
            // должны использоваться дефолтные scopes - crm, notifications
        ]);

        $defaultScopes = implode(',', AmoCRMOAuth::METADATA_BUTTON_AVAILABLE_SCOPES);
        $this->assertStringContainsString('data-scopes="' . $defaultScopes . '"', $result);
    }

    /**
     * Проверяем поддержку Kommo
     * @throws AmoCRMOAuthButtonConfigurationException
     */
    public function testKommoButtonGeneration(): void
    {
        $result = $this->oauthClient->getOAuthButton([
            'is_kommo' => true,
            'is_metadata' => true,
            'name' => 'Test',
            'description' => 'Test',
            'scopes' => ['crm'],
            'secrets_uri' => 'https://secrets.com'
        ]);

        $this->assertStringContainsString('class="kommo_oauth"', $result);
        $this->assertStringContainsString('kommo.com', $result);
    }

    /**
     * Проверяем ошибку при отсутствии secrets_uri для metadata кнопки
     * @return void
     * @throws AmoCRMOAuthButtonConfigurationException
     */
    public function testMetadataButtonWithoutSecretsUriThrowsException(): void
    {
        $this->expectException(AmoCRMOAuthButtonConfigurationException::class);
        $this->expectExceptionMessage('secrets_uri');

        // Создаем клиент без secrets_uri
        $clientWithoutSecrets = new AmoCRMOAuth(
            'test_client_id',
            'test_secret',
            'https://redirect.com'
        );

        $clientWithoutSecrets->getOAuthButton([
            'is_metadata' => true,
            'name' => 'Test',
            'description' => 'Test',
            'scopes' => ['crm']
        ]);
    }

    /**
     * Проверяем работу с пустым массивом scopes
     * Должны подставиться дефолтные scopes
     * @throws AmoCRMOAuthButtonConfigurationException
     */
    public function testMetadataButtonWithEmptyScopesArrayUsesDefaults(): void
    {
        $result = $this->oauthClient->getOAuthButton([
            'is_metadata' => true,
            'name' => 'Test',
            'description' => 'Test',
            'scopes' => [], // Пустой массив
            'secrets_uri' => 'https://secrets.com'
        ]);

        $defaultScopes = implode(',', AmoCRMOAuth::METADATA_BUTTON_AVAILABLE_SCOPES);
        $this->assertStringContainsString('data-scopes="' . $defaultScopes . '"', $result);
    }
}
