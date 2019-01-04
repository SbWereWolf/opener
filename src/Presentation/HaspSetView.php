<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 2:22
 */

namespace Presentation;


use Latch\IHasp;
use Latch\IHaspSet;

class HaspSetView implements View
{
    const ID = 'id';
    const POINT = 'point';
    const REMARK = 'remark';

    private $dataSet = null;

    public function __construct(IHaspSet $dataSet)
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
            /** @var IHasp $element */
            $record = array(
                self::ID => $element->getId(),
                self::POINT => $element->getPoint(),
                self::REMARK => $element->getRemark(),
            );
            $collection[] = $record;
        }
        return $collection;
    }
}
