<?php

namespace DataStorage\Session;


use BusinessLogic\Basis\Content;
use BusinessLogic\Session;
use BusinessLogic\Session\ISession;
use BusinessLogic\Session\SessionSet;
use DataStorage\Basis\DataAccess;
use LanguageFeatures\ArrayParser;

class SessionAccess extends DataAccess
{
    public function insertWithEmail(ISession $session): self
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

    private function processForOutput(\PDOStatement $request): self
    {
        $isSuccess = $this->execute($request)->isSuccess();

        $this->setData(new SessionSet());

        return $this;
    }

    public function insertWithToken(ISession $session): self
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

    public function select(Session\ISession $session): self
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

    private function processRead(\PDOStatement $request): self
    {
        $isSuccess = $this->execute($request)->isSuccess();

        $dataSet = array();
        if ($isSuccess) {
            $dataSet = $request->fetchAll(\PDO::FETCH_ASSOC);

            $rowCount = count($dataSet);
            $this->setRowCount($rowCount);
        }

        $shouldParseData = $isSuccess && $this->getRowCount() > 0;
        $data = new SessionSet();
        if ($shouldParseData) {
            $data = $this->parseOutput($dataSet);
        }

        $this->setData($data);

        return $this;
    }

    /**
     * @param array $dataSet
     * @return Content
     */
    private function parseOutput(array $dataSet): Content
    {
        $result = new SessionSet();
        foreach ($dataSet as $dataRow) {
            $parser = new ArrayParser($dataRow);

            $finish = $parser->getIntegerField('finish');

            $item = (new Session())
                ->setFinish($finish);

            $result->push($item);
        }

        return $result;
    }

    public function delete(ISession $lease): self
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
