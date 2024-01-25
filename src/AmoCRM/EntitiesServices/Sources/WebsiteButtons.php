<?php

declare(strict_types=1);

namespace AmoCRM\EntitiesServices\Sources;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\Sources\WebsiteButtonsCollection;
use AmoCRM\EntitiesServices\BaseEntity;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Sources\WebsiteButtonModel;

/**
 * @method WebsiteButtonModel|null getOne($id, array $with = [])
 * @method WebsiteButtonsCollection|null get(BaseEntityFilter $filter = null, array $with = [])
 */
class WebsiteButtons extends BaseEntity
{
    use PageMethodsTrait;

    /**
     * @var string
     */
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/website_buttons';

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
}
