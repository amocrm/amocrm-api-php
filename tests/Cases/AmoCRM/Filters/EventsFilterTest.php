<?php

declare(strict_types=1);

namespace Cases\AmoCRM\Filters;

use AmoCRM\Filters\EventsFilter;
use PHPUnit\Framework\TestCase;

class EventsFilterTest extends TestCase
{
    /**
     * @var EventsFilter
     */
    private $eventsFilter;

    public function setUp(): void
    {
        $this->eventsFilter = new EventsFilter();
    }

    /**
     * @dataProvider getValidArrayOrNumericFilter
     *
     * @param $entityIds
     * @param $expected
     */
    public function testValidEntityIds($entityIds, $expected)
    {
        $this->eventsFilter->setEntityIds($entityIds);
        $this->assertEquals($expected, array_values($this->eventsFilter->getEntityIds()));
    }

    /**
     * @dataProvider getInvalidArrayOrNumericFilter
     *
     * @param $entityIds
     */
    public function testInvalidEntityIds($entityIds)
    {
        $this->eventsFilter->setEntityIds($entityIds);
        $this->assertNull($this->eventsFilter->getEntityIds());
    }

    /**
     * @return array
     */
    public function getInvalidArrayOrNumericFilter()
    {
        return [
            [
                -1,
            ],
            [
                [
                    -1,
                    -100,
                ],
            ],
            [
                [
                    -100,
                ],
            ],
            [
                "hello",
            ],
            [
                [
                    true,
                    false,
                ],
            ],
            [
                false,
            ],
            [
                null,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getValidArrayOrNumericFilter()
    {
        return [
            [
                0, [0],
            ],
            [
                100, [100],
            ],
            [
                "100", [100],
            ],
            [
                [1, 2, 3, 4], [1, 2, 3, 4],
            ],
            [
                [-1, 1, 2], [1, 2],
            ],
        ];
    }
}
