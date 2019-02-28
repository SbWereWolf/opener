<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 5:24
 */

namespace DataStorage\Unlock;


use BusinessLogic\Unlock\IUnlock;
use DataStorage\Basis\IDataAccess;

interface UnlockAccess extends IDataAccess
{
    public function insert(IUnlock $unlock): self;

    public function delete(IUnlock $unlock): self;

    public function selectByPoint(IUnlock $unlock): self;

    public function selectByShutter(IUnlock $unlock): self;

}
