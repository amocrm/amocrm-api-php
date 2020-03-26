<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\LeadsCollection;
use AmoCRM\Models\LeadModel;

class Leads extends BaseEntity
{
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/leads';

    protected $collectionClass = LeadsCollection::class;

    protected $itemClass = LeadModel::class;

    protected function getEntitiesFromResponse(array $response): array
    {
        $entities = [];

        if (isset($response[AmoCRMApiRequest::EMBEDDED]) && isset($response[AmoCRMApiRequest::EMBEDDED]['leads'])) {
            $entities = $response[AmoCRMApiRequest::EMBEDDED]['leads'];
        }

        return $entities;
    }
}
