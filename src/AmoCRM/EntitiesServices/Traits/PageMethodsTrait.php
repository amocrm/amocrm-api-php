<?php

namespace AmoCRM\EntitiesServices\Traits;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiPageNotAvailableException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;

use function is_null;

trait PageMethodsTrait
{
    /**
     * @var AmoCRMApiRequest
     */
    protected $request;

    /**
     * @param HasPagesInterface|BaseApiCollection $collection
     * @return HasPagesInterface
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiPageNotAvailableException
     */
    public function nextPage(HasPagesInterface $collection): HasPagesInterface
    {
        $nextPageLink = $collection->getNextPageLink();
        if (is_null($nextPageLink)) {
            throw new AmoCRMApiPageNotAvailableException(
                "No next page available"
            );
        }

        $queryParams = parse_url($nextPageLink)['query'];
        parse_str($queryParams, $parsedQueryParams);
        $response = $this->request->get($nextPageLink, $parsedQueryParams);

        return $this->createCollection($response);
    }

    /**
     * @param HasPagesInterface|BaseApiCollection $collection
     * @return HasPagesInterface
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiPageNotAvailableException
     */
    public function prevPage(HasPagesInterface $collection): HasPagesInterface
    {
        $prevPageLink = $collection->getPrevPageLink();
        if (is_null($prevPageLink)) {
            throw new AmoCRMApiPageNotAvailableException(
                "No prev page available"
            );
        }

        $queryParams = parse_url($prevPageLink)['query'];
        parse_str($queryParams, $parsedQueryParams);
        $response = $this->request->get($prevPageLink, $parsedQueryParams);

        return $this->createCollection($response);
    }
}
