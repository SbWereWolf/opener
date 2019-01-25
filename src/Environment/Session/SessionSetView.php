<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 2:22
 */

namespace Environment\Session;


use BusinessLogic\Basis\Content;
use BusinessLogic\Session\ISession;

class SessionSetView implements \Environment\Basis\View
{
    const TOKEN = 'token';
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
