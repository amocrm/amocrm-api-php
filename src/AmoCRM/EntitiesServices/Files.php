<?php

namespace AmoCRM\EntitiesServices;

use AmoCRM\Models\Files\FileModel;
use AmoCRM\Models\Files\FileUploadModel;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\FilesCollection;
use AmoCRM\EntitiesServices\Interfaces\HasPageMethodsInterface;
use AmoCRM\EntitiesServices\Traits\PageMethodsTrait;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;

/**
 * Class Files
 *
 * @package AmoCRM\EntitiesServices
 * @method null|FileModel getOne($uuid, array $with = [])
 */
class Files extends BaseEntity implements HasPageMethodsInterface, HasDeleteMethodInterface
{
    use PageMethodsTrait;

    /**
     * @var string
     */
    protected $method = AmoCRMApiClient::DRIVE_API_VERSION;

    /**
     * @var string
     */
    protected $collectionClass = FilesCollection::class;

    /**
     * @var string
     */
    public const ITEM_CLASS = FileModel::class;

    protected function getMethod(): string
    {
        return $this->method . '/files';
    }

    /**
     * @param BaseApiCollection $collection
     *
     * @return BaseApiCollection
     * @throws NotAvailableForActionException
     */
    public function add(BaseApiCollection $collection): BaseApiCollection
    {
        throw new NotAvailableForActionException('Method not available for this entity, use upload');
    }

    /**
     * @param BaseApiModel $model
     *
     * @return BaseApiModel
     * @throws NotAvailableForActionException
     */
    public function addOne(BaseApiModel $model): BaseApiModel
    {
        throw new NotAvailableForActionException('Method not available for this entity, use upload');
    }


    /**
     * Загрузка файла
     * @param FileUploadModel $model
     *
     * @return FileModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function uploadOne(FileUploadModel $model): FileModel
    {
        $fileResource = fopen($model->getLocalPath(), "rb");
        $contentType = mime_content_type($fileResource);

        $body = [
            'file_name'    => $model->getName() ?? basename($model->getLocalPath()),
            'file_size'    => fstat($fileResource)['size'],
            'content_type' => $contentType,
        ];

        if ($model->isWithPreview()) {
            $body['with_preview'] = $model->isWithPreview();
        }

        if (!empty($model->getFileUuid())) {
            $body['file_uuid'] = $model->getFileUuid();
        }

        if (!is_null($model->getCreatedByType()) && !is_null($model->getCreatedBy())) {
            $body['created_by'] = [
                'id' => $model->getCreatedBy(),
                'type' => $model->getCreatedByType(),
            ];
        }

        $response = $this->request->post($this->method . '/sessions', $body);

        $uploadUrl = $response['upload_url'];
        $maxPartSize = (int)$response['max_part_size'];

        while (($buffer = stream_get_contents($fileResource, $maxPartSize)) !== false) {
            $response = $this->request->post(
                $uploadUrl,
                $buffer,
                [],
                ['Content-Type' => $contentType],
                false,
                true
            );

            if (!empty($response['next_url'])) {
                $uploadUrl = $response['next_url'];
            } else {
                break;
            }

            if (feof($fileResource)) {
                break;
            }
        }

        fclose($fileResource);

        return FileModel::fromArray($response);
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
     * Обновление одной конкретной сущности
     * @param BaseApiModel|FileModel $apiModel
     *
     * @return BaseApiModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function updateOne(BaseApiModel $apiModel): BaseApiModel
    {
        if (!$apiModel instanceof HasIdInterface) {
            throw new InvalidArgumentException('Entity should have getId method');
        }

        $response = $this->request->patch($this->getMethod() . '/' . $apiModel->getUuid(), $apiModel->toApi(0));
        return $this->processUpdateOne($apiModel, $response);
    }

    /**
     * @param BaseApiModel|FileModel $apiModel
     * @param array $with
     *
     * @return FileModel
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function syncOne(BaseApiModel $apiModel, $with = []): BaseApiModel
    {
        return $this->mergeModels($this->getOne($apiModel->getUuid(), $with), $apiModel);
    }

    /**
     * @param BaseApiModel $model
     * @param array $response
     *
     * @return BaseApiModel
     */
    protected function processUpdateOne(BaseApiModel $model, array $response): BaseApiModel
    {
        $this->processModelAction($model, $response);

        return $model;
    }

    /**
     * @param array $response
     *
     * @return array
     */
    protected function getEntitiesFromResponse(array $response): array
    {
        return $response[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::FILES] ?? [];
    }

    /**
     * @param BaseApiModel $model
     *
     * @return bool
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws NotAvailableForActionException
     */
    public function deleteOne(BaseApiModel $model): bool
    {
        /** @var BaseApiCollection $collection */
        $collection = new $this->collectionClass();
        $collection->add($model);

        return $this->delete($collection);
    }

    /**
     * @param BaseApiCollection|FilesCollection $collection
     *
     * @return bool
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     */
    public function delete(BaseApiCollection $collection): bool
    {
        $result = $this->request->delete($this->getMethod(), $collection->toDeleteApi());

        return $result['result'] ?? false;
    }

    /**
     * @param BaseApiModel|FileModel $apiModel
     * @param array $entity
     */
    protected function processModelAction(BaseApiModel $apiModel, array $entity): void
    {
        if (isset($entity['id'])) {
            $apiModel->setId($entity['id']);
        }

        if (isset($entity['uuid'])) {
            $apiModel->setUuid($entity['uuid']);
        }

        if (isset($entity['version_uuid'])) {
            $apiModel->setVersionUuid($entity['version_uuid']);
        }

        if (isset($entity['size'])) {
            $apiModel->setSize($entity['size']);
        }

        if (isset($entity['metadata']['extension'])) {
            $apiModel->setExtension($entity['metadata']['extension']);
        }

        if (isset($entity['metadata']['mime_type'])) {
            $apiModel->setMimeType($entity['metadata']['mime_type']);
        }

        //todo other fields
    }
}
