<?php

declare(strict_types=1);

namespace Cases\Rights;

use AmoCRM\Models\Rights\RightModel;
use PHPUnit\Framework\TestCase;

use function reset;

class CatalogRightsTest extends TestCase
{
    public function getPriorityRightsData(): array
    {
        $rightsData = [];
        $caseAllOk = [
            [
                RightModel::ACTION_ADD    => RightModel::RIGHTS_FULL,
                RightModel::ACTION_VIEW   => RightModel::RIGHTS_FULL,
                RightModel::ACTION_EDIT   => RightModel::RIGHTS_LINKED,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_DENIED,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_FULL,
            ],
            [
                RightModel::ACTION_ADD    => RightModel::RIGHTS_FULL,
                RightModel::ACTION_VIEW   => RightModel::RIGHTS_FULL,
                RightModel::ACTION_EDIT   => RightModel::RIGHTS_LINKED,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_DENIED,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_FULL,
            ],
        ];
        $rightsData[] = $caseAllOk;

        $caseWrongPriority = [
            [
                RightModel::ACTION_ADD    => RightModel::RIGHTS_FULL,
                RightModel::ACTION_VIEW   => RightModel::RIGHTS_LINKED,
                RightModel::ACTION_EDIT   => RightModel::RIGHTS_FULL,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_LINKED,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_FULL,
            ],
            [
                RightModel::ACTION_ADD    => RightModel::RIGHTS_FULL,
                RightModel::ACTION_VIEW   => RightModel::RIGHTS_LINKED,
                RightModel::ACTION_EDIT   => RightModel::RIGHTS_LINKED,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_LINKED,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_LINKED,
            ],
        ];
        $rightsData[] = $caseWrongPriority;

        $caseWrongPerm = [
            [
                RightModel::ACTION_ADD    => RightModel::RIGHTS_FULL,
                RightModel::ACTION_VIEW   => RightModel::RIGHTS_FULL,
                RightModel::ACTION_EDIT   => RightModel::RIGHTS_FULL,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_ONLY_RESPONSIBLE,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_FULL,
            ],
            [
                RightModel::ACTION_ADD    => RightModel::RIGHTS_FULL,
                RightModel::ACTION_VIEW   => RightModel::RIGHTS_FULL,
                RightModel::ACTION_EDIT   => RightModel::RIGHTS_FULL,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_DENIED,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_FULL,
            ],
        ];
        $rightsData[] = $caseWrongPerm;

        $caseEmptyAction = [
            [
                RightModel::ACTION_ADD    => RightModel::RIGHTS_FULL,
                RightModel::ACTION_VIEW   => RightModel::RIGHTS_FULL,
                RightModel::ACTION_EDIT   => RightModel::RIGHTS_FULL,
            ],
            [
                RightModel::ACTION_ADD    => RightModel::RIGHTS_FULL,
                RightModel::ACTION_VIEW   => RightModel::RIGHTS_FULL,
                RightModel::ACTION_EDIT   => RightModel::RIGHTS_FULL,
                RightModel::ACTION_DELETE => RightModel::RIGHTS_DENIED,
                RightModel::ACTION_EXPORT => RightModel::RIGHTS_DENIED,
            ],
        ];
        $rightsData[] = $caseEmptyAction;

        return $rightsData;
    }

    /**
     * @dataProvider getPriorityRightsData
     *
     * @param array $rights
     * @param array $rightsReference
     */
    public function testRightsPriority(array $rights, array $rightsReference): void
    {
        $rightsModel = (new RightModel())->setCatalogRights([['catalog_id' => 1, 'rights' => $rights]]);
        $rightsFromModel = $rightsModel->getCatalogRights();
        $rightsFromModel = reset($rightsFromModel)['rights'] ?? [];

        $this::assertEquals($rightsReference, $rightsFromModel, 'Rights are not equals');
    }
}