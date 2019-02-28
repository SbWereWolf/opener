<?php

namespace DataStorage\Session;


use BusinessLogic\Session\ISession;
use DataStorage\Basis\IDataAccess;

interface SessionAccess extends IDataAccess
{
    public function insertWithEmail(ISession $session): self;

    public function insertWithToken(ISession $session): self;

    public function select(ISession $session): self;

    public function delete(ISession $lease): self;
}
