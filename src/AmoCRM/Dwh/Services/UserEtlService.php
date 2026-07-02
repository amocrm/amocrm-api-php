<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\UserDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;

class UserEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];

        $filter = null;

        $page = 1;
        do {
            try {
                if ($filter && method_exists($filter, 'setPage')) {
                    $filter->setPage($page);
                }
                $collection = $this->apiClient->users()->get($filter);
            } catch (AmoCRMApiException $e) {
                break;
            }

            if ($collection === null || $collection->isEmpty()) {
                break;
            }

            foreach ($collection as $model) {
                $this->syncEntity($model, $stats);
            }

            try {
                $collection = $this->apiClient->users()->nextPage($collection);
            } catch (AmoCRMApiException $e) {
                break;
            }

            $page++;
        } while ($collection !== null && !$collection->isEmpty());

        return $stats;
    }

    private function syncEntity($model, array &$stats): void
    {
        $entityId = $model->getId();

        $dwh = new UserDwhModel();
        $dwh->setAccountId($this->accountId);
        $dwh->setUsersId($entityId);

        $this->mapFields($dwh, $model);

        $this->db->upsert(
            'amocrm_users',
            $dwh->toInsertArray(),
            ['account_id', 'users_id']
        );

        $stats['processed']++;
    }

    private function mapFields(UserDwhModel $dwh, $model): void
    {
        $dwh->setName($this->toDateTimeString($model->getName()));
        $dwh->setEmail($this->toDateTimeString($model->getEmail()));
        $dwh->setLang($this->toDateTimeString($model->getLang()));
        $dwh->setPhoneNumber($this->toDateTimeString($model->getPhoneNumber()));
        $dwh->setRights($this->toDateTimeString($model->getRights()));
        $dwh->setRoleId($this->toDateTimeString($model->getRoleId()));
        $dwh->setGroupId($this->toDateTimeString($model->getGroupId()));
        $dwh->setIsAdmin($this->toDateTimeString($model->getIsAdmin()));
        $dwh->setIsFree($this->toDateTimeString($model->getIsFree()));
        $dwh->setIsActive($this->toDateTimeString($model->getIsActive()));
        $dwh->setCreatedAt($this->toDateTimeString($model->getCreatedAt()));
        $dwh->setUpdatedAt($this->toDateTimeString($model->getUpdatedAt()));
    }
}
