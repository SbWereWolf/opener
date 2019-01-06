<?php

namespace DataStorage;


use LanguageFeatures\ArrayParser;
use Latch\Content;
use Latch\ILease;
use Latch\Lease;
use Latch\LeaseSet;

class LeaseAccess extends DataAccess
{
    private $data = null;

    public function getActual(): self
    {
        $requestText = '
SELECT
       id,
       user_id,
       shutter_id,
       start,
       finish,
       occupancy_type_id
FROM
     lease
WHERE
    finish>strftime(\'%s\',\'now\')
    OR finish is NULL
    OR finish = 0;
   ';
        $request = $this->prepareRequest($requestText);
        $this->processSelect($request);

        return $this;
    }

    /**
     * @param $requestText
     * @return bool|\PDOStatement
     */
    private function prepareRequest($requestText)
    {
        $dbConnection = $this->getDbConnection();
        $request = $dbConnection->prepare($requestText);
        return $request;
    }

    /**
     * @param $requestText
     * @return bool
     */
    private function processSelect(\PDOStatement $request): self
    {
        $isSuccess = $request->execute();

        if ($isSuccess) {
            $this->setSuccessStatus();
            $dataSet = $request->fetchAll(\PDO::FETCH_ASSOC);
            $isSuccess = $this->parseOutput($dataSet);
        }

        if (!$isSuccess) {
            $this->setFailStatus();
        }

        return $this;
    }

    /**
     * @param $dataSet
     * @return bool
     */
    private function parseOutput(array $dataSet): bool
    {
        $result = new LeaseSet();
        foreach ($dataSet as $dataRow) {
            $parser = new ArrayParser($dataRow);

            $id = $parser->getIntegerField('id');
            $userId = $parser->getIntegerField('user_id');
            $shutterId = $parser->getIntegerField('shutter_id');
            $start = $parser->getIntegerField('start');
            $finish = $parser->getIntegerField('finish');
            $occupancyTypeId = $parser->getIntegerField('occupancy_type_id');

            $item = (new Lease())
                ->setFinish($finish)
                ->setId($id)
                ->setOccupancyTypeId($occupancyTypeId)
                ->setShutterId($shutterId)
                ->setStart($start)
                ->setUserId($userId);

            $result->push($item);
        }

        $isSuccess = $this->isSuccess();
        if ($isSuccess) {
            $result->setSuccessStatus();
        }
        if (!$isSuccess) {
            $result->setFailStatus();
        }

        $this->setData($result);

        return true;
    }

    public function insert(ILease $lease): self
    {
        $requestText = '
INSERT INTO 
  lease
(   
    user_id,
    shutter_id,
    start,
    finish,
    occupancy_type_id
)
VALUES(
    :USER_ID,
    :SHUTTER_ID,
    :START,
    :FINISH,
    :OCCUPANCY_TYPE_ID
)
RETURNING 
    id,
    user_id,
    shutter_id,
    start,
    finish,
    occupancy_type_id
;
   ';
        $request = $this->prepareRequest($requestText);

        $userId = $lease->getUserId();
        $shutterId = $lease->getShutterId();
        $start = $lease->getStart();
        $finish = $lease->getFinish();
        $occupancyTypeId = $lease->getOccupancyTypeId();

        $request->bindValue(':USER_ID', $userId, \PDO::PARAM_INT);
        $request->bindValue(':SHUTTER_ID', $shutterId, \PDO::PARAM_INT);
        $request->bindValue(':START', $start, \PDO::PARAM_INT);
        $request->bindValue(':FINISH', $finish, \PDO::PARAM_INT);
        $request->bindValue(':OCCUPANCY_TYPE_ID', $occupancyTypeId, \PDO::PARAM_INT);

        $this->processSelect($request);

        return $this;
    }

    public function update(ILease $lease): self
    {
        $requestText = '
UPDATE 
  lease
SET 
    user_id = :USER_ID,
    shutter_id = :SHUTTER_ID,
    start = :START,
    finish = :FINISH,
    occupancy_type_id = :OCCUPANCY_TYPE_ID
WHERE 
 id = :ID
;
   ';
        $request = $this->prepareRequest($requestText);

        $id = $lease->getId();
        $userId = $lease->getUserId();
        $shutterId = $lease->getShutterId();
        $start = $lease->getStart();
        $finish = $lease->getFinish();
        $occupancyTypeId = $lease->getOccupancyTypeId();

        $request->bindValue(':ID', $id, \PDO::PARAM_INT);
        $request->bindValue(':USER_ID', $userId, \PDO::PARAM_INT);
        $request->bindValue(':SHUTTER_ID', $shutterId, \PDO::PARAM_INT);
        $request->bindValue(':START', $start, \PDO::PARAM_INT);
        $request->bindValue(':FINISH', $finish, \PDO::PARAM_INT);
        $request->bindValue(':OCCUPANCY_TYPE_ID', $occupancyTypeId, \PDO::PARAM_INT);

        $this->processUpdate($request);

        $this->setData(new LeaseSet());

        return $this;
    }

    public function getCurrent(ILease $lease): self
    {
        $requestText = '
SELECT
       l.id AS id,
       l.user_id AS user_id,
       l.shutter_id AS shutter_id,
       l.start AS start,
       l.finish AS finish,
       l.occupancy_type_id AS occupancy_type_id
FROM
       session s
       join lease l
       on l.user_id = s.user_id
WHERE
       s.token = :TOKEN
       AND s.finish>strftime(\'%s\',\'now\')
       AND l.finish>strftime(\'%s\',\'now\')
       OR l.finish is NULL
       OR l.finish = 0)
;
   ';
        $request = $this->prepareRequest($requestText);

        $token = $lease->getToken();
        $request->bindValue(':TOKEN', $token);

        $this->processSelect($request);

        return $this;
    }

    public function getData(): Content
    {
        return $this->data;
    }

    /**
     * @param null $data
     */
    private function setData($data): self
    {
        $this->data = $data;
        return $this;
    }
}
