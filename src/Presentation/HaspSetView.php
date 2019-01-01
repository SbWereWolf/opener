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
        $collection = array();
        foreach ($this->dataSet->next() as $element => $key) {
            /** @var IHasp $element */
            $collection[$key][self::ID] = $element->getId();
            $collection[$key][self::POINT] = $element->getPoint();
            $collection[$key][self::REMARK] = $element->getRemark();
        }
        $result = json_encode($collection);

        return $result;
    }
}
