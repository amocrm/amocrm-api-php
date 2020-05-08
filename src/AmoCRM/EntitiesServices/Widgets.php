<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\WidgetsCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\WidgetModel;
use Exception;

/**
 * Class Widgets
 *
 * @package AmoCRM\EntitiesServices
 * @method WidgetModel getOne($id, array $with = []) : ?BaseApiModel
 */
class Widgets extends BaseEntity implements HasPageMethodsInterface
{
    use PageMethodsTrait;

    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::WIDGETS;

    protected $collectionClass = WidgetsCollection::class;

    protected $itemClass = WidgetModel::class;

    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::WIDGETS])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::WIDGETS];
        }

        return $entities;
    }

    /**
     * @param BaseApiCollection $collection
     * @return BaseApiCollection
     * @throws Exception
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        throw new Exception('Method not available for this entity');
    }

    /**
     * @param BaseApiCollection $collection
     * @return BaseApiCollection
     * @throws Exception
     */
    public function update(BaseApiCollection $collection): BaseApiCollection
    {
        throw new Exception('Method not available for this entity');
    }

    /**
     * @param BaseApiModel $apiModel
     * @return BaseApiModel
     * @throws Exception
     */
    public function updateOne(BaseApiModel $apiModel): BaseApiModel
    {
        throw new Exception('Method not available for this entity');
    }

    /**
     * Установка виджета
     *
     * @param WidgetModel $widgetModel
     *
     * @return WidgetModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function install(WidgetModel $widgetModel): WidgetModel
    {
        $response = $this->request->post(
            $this->getMethod() . '/' . $widgetModel->getCode(),
            $widgetModel->toApi()
        );
        foreach ($response as $key => $value) {
            $widgetModel->$key = $value;
        }

        return $widgetModel;
    }

    /**
     * Отключение виджета
     *
     * @param WidgetModel $widgetModel
     *
     * @return WidgetModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function uninstall(WidgetModel $widgetModel): WidgetModel
    {
        $response = $this->request->delete(
            $this->getMethod() . '/' . $widgetModel->getCode()
        );
        foreach ($response as $key => $value) {
            $widgetModel->$key = $value;
        }

        return $widgetModel;
    }
}
