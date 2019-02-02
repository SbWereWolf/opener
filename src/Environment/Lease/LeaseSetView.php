<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 2:22
 */

namespace Environment\Lease;


use BusinessLogic\Basis\Content;
use BusinessLogic\Lease\ILease;

class LeaseSetView implements \Environment\Basis\View
{
    const SHUTTER_ID = 'shutter-id';
    const LEASE_ID = 'lease-id';
    const START = 'start';
    const FINISH = 'finish';

    /** @var Content */
    private $dataSet = null;

    public function __construct(Content $dataSet)
    {
        $this->dataSet = $dataSet;
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
                self::SHUTTER_ID => $element->getShutterId(),
                self::LEASE_ID => $element->getId(),
                self::START => $element->getStart(),
                self::FINISH => $element->getFinish(),
            );

            $collection[] = $record;
        }
        return $collection;
    }
}
