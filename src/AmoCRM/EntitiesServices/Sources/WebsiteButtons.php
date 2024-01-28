<?php

declare(strict_types=1);

namespace AmoCRM\EntitiesServices\Sources;

use AmoCRM\Collections\Sources\WebsiteButtonsCollection;
use AmoCRM\Models\Sources\WebsiteButtonModel;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\EntitiesServices\BaseEntity;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Sources\WebsiteButtonCreateRequestModel;
use AmoCRM\Models\Sources\WebsiteButtonCreateResponseModel;
use AmoCRM\Models\Sources\WebsiteButtonUpdateRequestModel;

/**
 * @method WebsiteButtonModel|null getOne($id, array $with = [])
 * @method WebsiteButtonsCollection|null get(BaseEntityFilter $filter = null, array $with = [])
 */
class WebsiteButtons extends BaseEntity
{
    use PageMethodsTrait;

    private const ONLINECHAT_ENDPOINT = 'online_chat';

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/' . EntityTypesInterface::WEBSITE_BUTTONS;

    /**
     * @var string
     */
    protected $collectionClass = WebsiteButtonsCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = WebsiteButtonModel::class;

    /**
     * @param array $response
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        return $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::WEBSITE_BUTTONS] ?? [];
    }

    public function createAsync(WebsiteButtonCreateRequestModel $model): WebsiteButtonCreateResponseModel
    {
        $response = $this->request->post($this->getMethod(), $model->toApi());

        return WebsiteButtonCreateResponseModel::fromArray($response);
    }

    /**
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiNoContentException
     */
    public function addOnlineChatAsync(int $sourceId): void
    {
        $this->request->post(
            sprintf('%s/%d/%s', $this->getMethod(), $sourceId, self::ONLINECHAT_ENDPOINT)
        );
    }

    /**
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiNoContentException
     */
    public function updateAsync(WebsiteButtonUpdateRequestModel $model): WebsiteButtonModel
    {
        $response = $this->request->patch(
            sprintf('%s/%d', $this->getMethod(), $model->getSourceId()),
            $model->toApi()
        );

        return WebsiteButtonModel::fromArray($response);
    }
}
