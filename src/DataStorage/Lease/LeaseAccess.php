<?php

namespace DataStorage\Lease;


use BusinessLogic\Lease\ILease;
use DataStorage\Basis\IDataAccess;

interface LeaseAccess extends IDataAccess
{
    public function getShutterId(ILease $lease): self;

    public function findFreeHours(ILease $lease): self;

    public function getActual(): self;

    public function insert(ILease $lease): self;

    public function update(ILease $lease): self;

    public function getCurrent(ILease $lease): self;
}
