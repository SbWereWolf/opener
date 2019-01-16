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

    public function select(ISession $session): self
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
        $request->bindValue(':TOKEN', $token, \PDO::PARAM_INT);

        $this->processRead($request)->processSuccess();

        return $this;
    }

    private function processRead(\PDOStatement $request): self
    {
        $isSuccess = $this->execute($request)->isSuccess();

        if ($isSuccess) {
            $dataSet = $request->fetchAll(\PDO::FETCH_ASSOC);
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
        $request->bindValue(':TOKEN', $token);

        $this->processWrite($request)->processSuccess();

        return $this;
    }
}
