<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 2:22
 */

namespace Presentation;


use Latch\Content;
use Latch\IUser;

class UserSetView implements View
{
    const ID = 'id';
    const EMAIL = 'email';
    const SECRET = 'secret';

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
            /** @var IUser $element */
            $record = array(
                self::ID => $element->getId(),
                self::SECRET => $element->getSecret(),
                self::EMAIL => $element->getEmail(),
            );

            $collection[] = $record;
        }
        return $collection;
    }
}
