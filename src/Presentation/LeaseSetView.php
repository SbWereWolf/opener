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
    const SHUTTER_ID = 'shutter-id';
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
                self::FINISH => $element->getFinish(),
                self::SHUTTER_ID => $element->getShutterId(),
                self::START => $element->getStart(),
            );

            $collection[] = $record;
        }
        return $collection;
    }
}
