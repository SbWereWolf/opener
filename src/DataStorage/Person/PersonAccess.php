<?php

namespace DataStorage\Person;


use BusinessLogic\Person\IPerson;
use DataStorage\Basis\IDataAccess;

interface PersonAccess extends IDataAccess
{
    public function insert(IPerson $person): self;

    public function select(IPerson $person): self;
}
