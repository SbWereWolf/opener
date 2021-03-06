<?php

namespace DataStorage\Person;


use BusinessLogic\Person\IPerson;

class PersonAccessMysql extends CommonPersonAccess implements PersonAccess
{
    public function insert(IPerson $person): PersonAccess
    {
        $requestText = '
INSERT INTO 
  person
(   
    email,
    secret
)
VALUES(
    :EMAIL,
    :SECRET
)
;
   ';
        $request = $this->prepareRequest($requestText);

        $email = $person->getEmail();
        $secret = $person->getSecret();

        $request->bindValue(':EMAIL', $email, \PDO::PARAM_STR);
        $request->bindValue(':SECRET', $secret, \PDO::PARAM_STR);

        $this->processWrite($request)->processSuccess();

        return $this;
    }

    public function select(IPerson $person): PersonAccess
    {
        $requestText = '
SELECT
    secret
FROM 
  person
WHERE
    email = :EMAIL
;
   ';
        $request = $this->prepareRequest($requestText);

        $email = $person->getEmail();
        $request->bindValue(':EMAIL', $email, \PDO::PARAM_STR);

        $this->processRead($request)->processSuccess();

        return $this;
    }
}
