<?php

namespace DataStorage;


use LanguageFeatures\ArrayParser;
use Latch\Content;
use Latch\IUser;
use Latch\User;
use Latch\UserSet;

class UserAccess extends DataAccess
{
    private $data = null;

    private function prepareRequest(string $requestText)
    {
        $dbConnection = $this->getAccess();
        $request = $dbConnection->prepare($requestText);
        return $request;
    }

    private function processSelect(\PDOStatement $request): self
    {
        $isSuccess = $request->execute();

        if ($isSuccess) {
            $dataSet = $request->fetchAll(\PDO::FETCH_ASSOC);

            $rowCount = $request->rowCount();
            $this->setRowCount($rowCount);

            $data = $this->parseOutput($dataSet);
            $this->setData($data);

            $this->setSuccessStatus();
        }

        if (!$isSuccess) {
            $this->setFailStatus();
        }

        return $this;
    }

    private function parseOutput(array $dataSet): Content
    {
        $result = new UserSet();
        foreach ($dataSet as $dataRow) {
            $parser = new ArrayParser($dataRow);

            $id = $parser->getIntegerField('id');
            $email = $parser->getIntegerField('user_id');
            $secret = $parser->getIntegerField('shutter_id');

            $item = (new User())
                ->setId($id)
                ->setEmail($email)
                ->setSecret($secret);

            $result->push($item);
        }

        $isSuccess = $this->isSuccess();
        if ($isSuccess) {
            $result->setSuccessStatus();
        }
        if (!$isSuccess) {
            $result->setFailStatus();
        }

        return $result;
    }

    public function insert(IUser $user): self
    {
        $requestText = '
INSERT INTO 
  \'user\'
(   
    email,
    secret
)
VALUES(
    :EMAIL,
    :SECRET
)
RETURNING 
    id,
    email,
    secret
;
   ';
        $request = $this->prepareRequest($requestText);

        $email = $user->getEmail();
        $secret = $user->getSecret();

        $request->bindValue(':EMAIL', $email, \PDO::PARAM_STR);
        $request->bindValue(':SECRET', $secret, \PDO::PARAM_STR);

        $this->processSelect($request);

        return $this;
    }

    public function getData(): Content
    {
        return $this->data;
    }

    private function setData(Content $data): self
    {
        $this->data = $data;
        return $this;
    }

}
