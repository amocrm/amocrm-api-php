<?php

declare(strict_types=1);

namespace AmoCRM\EntitiesServices;

use AmoCRM\Models\ProductsSettingsModel;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;

/**
 * Class Products
 * Для работы с API товаров, есть только методы настроек
 *
 * @package AmoCRM\EntitiesServices
 */
class Products extends BaseEntity
{
    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION
        . '/' . EntityTypesInterface::CATALOGS
        . '/' . EntityTypesInterface::PRODUCTS;

    /**
     * @var string
     */
    protected $methodSettings = 'api/v' . AmoCRMApiClient::API_VERSION
        . '/' . EntityTypesInterface::CATALOGS
        . '/' . EntityTypesInterface::PRODUCTS
        . '/' . EntityTypesInterface::SETTINGS;

    /**
     * @var string
     */
    public const ITEM_CLASS = ProductsSettingsModel::class;

    /**
     * @param array $response
     *
     * @return array
     * @throws NotAvailableForActionException
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseEntityFilter|null $filter
     * @param array                 $with
     *
     * @return BaseApiCollection|null
     * @throws NotAvailableForActionException
     */
    public function get(BaseEntityFilter $filter = null, array $with = []): ?BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }


    /**
     * @param int|string $id
     * @param array $with
     *
     * @return BaseApiModel|null
     * @throws NotAvailableForActionException
     */
    public function getOne($id, array $with = []): ?BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiModel $model
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function addOne(BaseApiModel $model): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @param BaseApiModel $apiModel
     * @param array $with
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity');
    }

    /**
     * @return ProductsSettingsModel|null
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function settings(): ?ProductsSettingsModel
    {
        $response = $this->request->get($this->methodSettings);

        $class = static::ITEM_CLASS;
        /** @var BaseApiModel $entity */
        $entity = new $class();

         return !empty($response) ? $entity->fromArray($response) : null;
    }

    /**
     * @param ProductsSettingsModel $productsSettings
     *
     * @return BaseApiModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiNoContentException
     */
    public function updateSettings(ProductsSettingsModel $productsSettings)
    {
        $response = $this->request->patch($this->methodSettings, $productsSettings->toApi());

        return $this->processUpdateOne($productsSettings, $response);
    }

    /**
     * @param ProductsSettingsModel $model
     * @param array        $response
     *
     * @return BaseApiModel
     */
    protected function processUpdateOne(BaseApiModel $model, array $response): BaseApiModel
    {
        if (isset($response['is_enabled'])) {
            $model->setIsEnabled($response['is_enabled']);
        }

        return $model;
    }
}
