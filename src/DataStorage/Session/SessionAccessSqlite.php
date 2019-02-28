<?php

namespace DataStorage\Session;


use BusinessLogic\Session\ISession;

class SessionAccessSqlite extends CommonSessionAccess implements SessionAccess
{
    public function insertWithEmail(ISession $session): SessionAccess
    {
        $requestText = '
INSERT INTO 
  session
(   
    token,
    finish,
    user_id    
)
VALUES(
    :TOKEN,
    strftime("%s", "now")+60*15,
    (select id from "user" where email = :EMAIL)
)
;
   ';
        $request = $this->prepareRequest($requestText);

        $token = $session->getToken();
        $email = $session->getEmail();

        $request->bindValue(':TOKEN', $token, \PDO::PARAM_STR);
        $request->bindValue(':EMAIL', $email, \PDO::PARAM_STR);

        $this->processForOutput($request)->processSuccess();

        return $this;
    }

    public function insertWithToken(ISession $session): SessionAccess
    {
        $requestText = '
INSERT INTO 
  session
(   
    token,
    finish,
    user_id    
)
VALUES(
    :TOKEN,
    strftime("%s", "now")+60*15,
    (SELECT COALESCE(MAX(user_id), 0) AS user_id FROM session WHERE token =:TOKEN)
)
;
   ';
        $request = $this->prepareRequest($requestText);

        $token = $session->getToken();

        $request->bindValue(':TOKEN', $token, \PDO::PARAM_STR);

        $this->processWrite($request)->processSuccess();

        return $this;
    }

    public function select(ISession $session): SessionAccess
    {
        $requestText = '
SELECT 
    COALESCE(MAX(finish), 0) as finish
FROM
    session
WHERE 
    token = :TOKEN
    AND finish > strftime("%s", "now")
;
   ';
        $request = $this->prepareRequest($requestText);

        $token = $session->getToken();
        $request->bindValue(':TOKEN', $token, \PDO::PARAM_STR);

        $this->processRead($request)->processSuccess();

        return $this;
    }

    public function delete(ISession $lease): SessionAccess
    {
        $requestText = '
DELETE
FROM
    session
WHERE 
    token = :TOKEN
;
   ';
        $request = $this->prepareRequest($requestText);

        $token = $lease->getToken();
        $request->bindValue(':TOKEN', $token, \PDO::PARAM_STR);

        $this->processWrite($request)->processSuccess();

        return $this;
    }
}
