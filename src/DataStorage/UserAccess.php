<?php

namespace DataStorage;


use LanguageFeatures\ArrayParser;
use Latch\Content;
use Latch\IUser;
use Latch\User;
use Latch\UserSet;

class UserAccess extends DataAccess
{
    private function processRead(\PDOStatement $request): self
    {
        $isSuccess = $this->execute($request)->isSuccess();

        if ($isSuccess) {
            $dataSet = $request->fetchAll(\PDO::FETCH_ASSOC);
        }

        $shouldParseData = $isSuccess && $this->getRowCount() > 0;
        $data = new UserSet();
        if ($shouldParseData) {
            $data = $this->parseOutput($dataSet);
        }

        $this->setData($data);

        return $this;
    }

    private function parseOutput(array $dataSet): Content
    {
        $result = new UserSet();
        foreach ($dataSet as $dataRow) {
            $parser = new ArrayParser($dataRow);

            $id = $parser->getIntegerField('id');
            $email = $parser->getStringField('email');
            $secret = $parser->getStringField('secret');

            $item = (new User())
                ->setId($id)
                ->setEmail($email)
                ->setSecret($secret);

            $result->push($item);
        }

        return $result;
    }

    public function insert(IUser $user): self
    {
        $requestText = '
INSERT INTO 
  "user"
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

        $email = $user->getEmail();
        $secret = $user->getSecret();

        $request->bindValue(':EMAIL', $email, \PDO::PARAM_STR);
        $request->bindValue(':SECRET', $secret, \PDO::PARAM_STR);

        $this->processWrite($request)->processSuccess();

        return $this;
    }

    public function select(IUser $user): self
    {
        $requestText = '
SELECT
    id,
    email,
    secret
FROM 
  "user"
WHERE
    email = :EMAIL
;
   ';
        $request = $this->prepareRequest($requestText);

        $email = $user->getEmail();
        $request->bindValue(':EMAIL', $email, \PDO::PARAM_STR);

        $this->processRead($request)->processSuccess();

        return $this;
    }
}
