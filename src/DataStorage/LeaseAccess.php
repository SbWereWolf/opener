<?php

namespace DataStorage;


use LanguageFeatures\ArrayParser;
use Latch\ILeaseSet;
use Latch\Lease;
use Latch\LeaseSet;

class LeaseAccess extends DataAccess
{
    private $data = null;

    public function getActual(): LeaseAccess
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
    datetime(finish, \'unixepoch\')<datetime(\'now\')
   OR finish is NULL;
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
    private function processSelect(\PDOStatement $request): LeaseAccess
    {
        $isSuccess = $request->execute();

        if ($isSuccess) {
            $dataSet = $request->fetchAll(\PDO::FETCH_ASSOC);
            $isSuccess = $this->parseOutput($dataSet);
        }

        if ($isSuccess) {
            $this->setSuccessStatus();
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
            $user_id = $parser->getIntegerField('user_id');
            $shutter_id = $parser->getIntegerField('shutter_id');
            $start = $parser->getIntegerField('start');
            $finish = $parser->getIntegerField('finish');
            $occupancy_type_id = $parser->getIntegerField('occupancy_type_id');

            $item = (new Lease())
                ->setFinish($finish)
                ->setId($id)
                ->setOccupancyTypeId($occupancy_type_id)
                ->setShutterId($shutter_id)
                ->setStart($start)
                ->setUserId($user_id);

            $result->push($item);
        }

        $isSuccess = $this->isSuccess();
        if ($isSuccess) {
            $result->setSuccessStatus();
        }
        if (!$isSuccess) {
            $result->setFailStatus();
        }
        $this->data = $result;

        return true;
    }

    public function getCurrent(Lease $lease): LeaseAccess
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
       AND datetime(s.finish, \'unixepoch\')<datetime(\'now\')
       AND (datetime(l.finish, \'unixepoch\')<datetime(\'now\')
       OR l.finish is NULL)
;
   ';
        $request = $this->prepareRequest($requestText);

        $token = $lease->getToken();
        $request->bindValue(':TOKEN', $token);

        $this->processSelect($request);

        return $this;
    }

    public function getData(): ILeaseSet
    {
        return $this->data;
    }
}
