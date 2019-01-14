<?php

namespace DataStorage;


use LanguageFeatures\ArrayParser;
use Latch\Content;
use Latch\ISession;
use Latch\Session;
use Latch\SessionSet;

class SessionAccess extends DataAccess
{
    public function insertWithEmail(ISession $session): self
    {
        $requestText = "
INSERT INTO 
  session
(   
    token,
    finish,
    user_id    
)
VALUES(
    :TOKEN,
    :FINISH,
    (select id from 'user' where email = :EMAIL)
)
;
   ";
        $request = $this->prepareRequest($requestText);

        $token = $session->getToken();
        $finish = $session->getFinish();
        $email = $session->getEmail();

        $request->bindValue(':TOKEN', $token, \PDO::PARAM_STR);
        $request->bindValue(':FINISH', $finish, \PDO::PARAM_INT);
        $request->bindValue(':EMAIL', $email, \PDO::PARAM_STR);

        $this->processWrite($request)->processSuccess();

        return $this;
    }

    public function insertWithUserId(ISession $session): self
    {
        $requestText = "
INSERT INTO 
  session
(   
    token,
    finish,
    user_id    
)
VALUES(
    :TOKEN,
    :FINISH,
    :USER_ID
)
;
   ";
        $request = $this->prepareRequest($requestText);

        $token = $session->getToken();
        $finish = $session->getFinish();
        $userId = $session->getUserId();

        $request->bindValue(':TOKEN', $token, \PDO::PARAM_STR);
        $request->bindValue(':FINISH', $finish, \PDO::PARAM_INT);
        $request->bindValue(':USER_ID', $userId, \PDO::PARAM_INT);

        $this->processWrite($request)->processSuccess();

        return $this;
    }

    public function select(ISession $session): self
    {
        $requestText = '
SELECT 
    MAX(finish) as finish,
    MAX(user_id) as user_id
FROM
    session
WHERE 
 token = :TOKEN
;
   ';
        $request = $this->prepareRequest($requestText);

        $token = $session->getToken();
        $request->bindValue(':TOKEN', $token, \PDO::PARAM_INT);

        $this->processRead($request)->processSuccess();

        return $this;
    }

    private function processRead(\PDOStatement $request): self
    {
        $isSuccess = $this->execute($request);

        if ($isSuccess) {
            $dataSet = $request->fetchAll(\PDO::FETCH_ASSOC);
            $this->setSuccessStatus();
        }

        if (!$isSuccess) {
            $this->setFailStatus();
        }

        $shouldParseData = $this->isSuccess() && $this->getRowCount() > 0;
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
            $userId = $parser->getIntegerField('user_id');

            $item = (new Session())
                ->setFinish($finish)
                ->setUserId($userId);

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
        $request->bindValue(':TOKEN', $token);

        $this->processWrite($request)->processSuccess();

        return $this;
    }
}
