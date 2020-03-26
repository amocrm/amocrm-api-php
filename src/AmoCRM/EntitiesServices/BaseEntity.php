<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Client\AmoCRMApiRequest;

class BaseEntity
{
    /**
     * @var string
     * override in child
     */
    protected $method = '';

    /**
     * @var AmoCRMApiRequest
     */
    private $request;

    public function __construct(AmoCRMApiRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @param array $filter
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     */
    public function get(array $filter = [])
    {
        $response = $this->request->get($this->method, $filter);

        return $response;
    }

    /**
     * //todo array filter to filter object
     * @param array $filter
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     */
    public function add(array $filter)
    {
        $response = $this->request->post($this->method, $filter);
    }

    /**
     * //todo array filter to filter object
     * @param array $filter
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     */
    public function update(array $filter)
    {
        $response = $this->request->get($this->method, $filter);
    }
}
