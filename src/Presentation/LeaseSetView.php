<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 2:22
 */

namespace Presentation;


use Latch\Content;
use Latch\ILease;

class LeaseSetView implements View
{
    const ID = 'id';
    const SHUTTER_ID = 'shutter-id';
    const START = 'start';
    const FINISH = 'finish';
    const OCCUPANCY_TYPE_ID = 'occupancy-type-id';

    /** @var Content */
    private $dataSet = null;

    public function __construct(Content $dataSet)
    {
        $this->dataSet = $dataSet;
    }

    public function toJson(): string
    {
        $collection = $this->toArray();
        $result = json_encode($collection);

        return $result;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $collection = array();
        foreach ($this->dataSet->next() as $element) {
            /** @var ILease $element */
            $record = array(
                self::FINISH => $element->getFinish(),
                self::ID => $element->getId(),
                self::OCCUPANCY_TYPE_ID => $element->getOccupancyTypeId(),
                self::SHUTTER_ID => $element->getShutterId(),
                self::START => $element->getStart(),
            );

            $collection[] = $record;
        }
        return $collection;
    }
}
