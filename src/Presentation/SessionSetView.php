<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 2:22
 */

namespace Presentation;


use Latch\Content;
use Latch\ISession;

class SessionSetView implements View
{
    const TOKEN = 'token';
    const FINISH = 'finish';

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
            /** @var ISession $element */
            $record = array(
                self::FINISH => $element->getFinish(),
                self::TOKEN => $element->getToken(),
            );

            $collection[] = $record;
        }
        return $collection;
    }
}
