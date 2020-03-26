<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiClient;

class Leads extends BaseEntity
{
    protected $method = 'api/v' . AmoCRMApiClient::API_VERSION . '/leads';
}
