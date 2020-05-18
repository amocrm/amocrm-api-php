<?php

namespace AmoCRM\EntitiesServices\Traits;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Filters\BaseEntityFilter;
use AmoCRM\Filters\LinksFilter;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\LinkModel;

trait LinkMethodsTrait
{
    /**
     * @var AmoCRMApiRequest
     */
    protected $request;

    /**
     * @param array $response
     *
     * @return LinksCollection
     */
    protected function getLinksCollectionFromResponse(array $response): LinksCollection
    {
        $links = new LinksCollection();

        $responseLinks = [];
        if (
            isset($response[AmoCRMApiRequest::EMBEDDED])
            && isset($response[AmoCRMApiRequest::EMBEDDED]['links'])
        ) {
            $responseLinks = $response[AmoCRMApiRequest::EMBEDDED]['links'];
        }

        if (!empty($responseLinks)) {
            $links = $links->fromArray($responseLinks);
        }

        return $links;
    }

    /**
     * @param BaseApiModel|HasIdInterface $model
     *
     * @param LinksFilter|null $filter
     *
     * @return LinksCollection
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function getLinks(BaseApiModel $model, LinksFilter $filter = null): LinksCollection
    {
        $queryParams = [];
        if ($filter instanceof BaseEntityFilter) {
            $queryParams = $filter->buildFilter();
        }

        $response = $this->request->get($this->getLinksMethod($model->getId()), $queryParams);

        return $this->getLinksCollectionFromResponse($response);
    }

    /**
     * @param BaseApiModel $mainEntity
     * @param LinksCollection|LinkModel $linkedEntities
     *
     * @return LinksCollection
     * @throws InvalidArgumentException
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function link(BaseApiModel $mainEntity, $linkedEntities): LinksCollection
    {
        if ($linkedEntities instanceof LinksCollection) {
            $body = $linkedEntities->toApi();
        } elseif ($linkedEntities instanceof LinkModel) {
            $body = [$linkedEntities->toApi()];
        }

        if (empty($body)) {
            throw new InvalidArgumentException('Linked entities are invalid');
        }

        foreach ($body as $link) {
            if (!in_array($link['to_entity_type'], $this->getAvailableLinkTypes(), true)) {
                throw new InvalidArgumentException('One of linked entities can not be linked to this type');
            }
        }

        if (!$mainEntity instanceof HasIdInterface) {
            throw new InvalidArgumentException('Main entity should have getId method');
        }

        $response = $this->request->post($this->getLinkMethod($mainEntity->getId(), true), $body);

        return $this->getLinksCollectionFromResponse($response);
    }

    /**
     * @param BaseApiModel $mainEntity
     * @param LinksCollection|LinkModel $linkedEntities
     *
     * @return bool
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws InvalidArgumentException
     */
    public function unlink(BaseApiModel $mainEntity, $linkedEntities): bool
    {
        $result = false;
        if ($linkedEntities instanceof LinksCollection) {
            $body = $linkedEntities->toApi();
        } elseif ($linkedEntities instanceof LinkModel) {
            $body = [$linkedEntities->toApi()];
        }

        if (empty($body)) {
            throw new InvalidArgumentException('Linked entities are invalid');
        }

        foreach ($body as $link) {
            if (!in_array($link['to_entity_type'], $this->getAvailableLinkTypes(), true)) {
                throw new InvalidArgumentException('One of linked entities can not be unlinked to this type');
            }
        }

        if (!$mainEntity instanceof HasIdInterface) {
            throw new InvalidArgumentException('Main entity should have getId method');
        }

        try {
            $this->request->post($this->getLinkMethod($mainEntity->getId(), false), $body);
        } catch (AmoCRMApiNoContentException $e) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param int $id
     * @param bool $isLink
     * @return string
     */
    protected function getLinkMethod(int $id, bool $isLink = true): string
    {
        $action = $isLink ? 'link' : 'unlink';

        return $this->getMethod() . '/' . $id . '/' . $action;
    }

    /**
     * @param int $id
     *
     * @return string
     */
    protected function getLinksMethod(int $id): string
    {
        return $this->getMethod() . '/' . $id . '/links';
    }

    /**
     * Какие типы могут быть привязанные к текущей сущности
     * @return array
     */
    abstract protected function getAvailableLinkTypes(): array;
}
