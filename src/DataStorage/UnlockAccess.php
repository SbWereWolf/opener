<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 5:24
 */

namespace DataStorage;


use Latch\Content;
use Latch\DataSet;
use Latch\IUnlock;

class UnlockAccess extends DataAccess
{
    public function insert(IUnlock $unlock): self
    {
        $requestText = '
INSERT INTO 
  unlock
(   
    shutter_id
)
VALUES(
    :SHUTTER_ID
)
;
   ';
        $request = $this->prepareRequest($requestText);

        $shutterId = $unlock->getShutterId();

        $request->bindValue(':SHUTTER_ID', $shutterId, \PDO::PARAM_INT);

        $this->process($request)->processSuccess();

        return $this;
    }

    public function delete(IUnlock $unlock): self
    {
        $requestText = '
DELETE
FROM
    unlock u
WHERE 
    EXISTS( 
    SELECT 
        NULL 
    FROM 
        shutter s 
        join unlock u2 on s.id = u2.shutter_id 
    WHERE 
        u2.shutter_id = u.shutter_id 
        AND s.point = :POINT
    )
;
   ';
        $request = $this->prepareRequest($requestText);

        $point = $unlock->getPoint();
        $request->bindValue(':POINT', $point, \PDO::PARAM_INT);

        $this->process($request)->processSuccess();

        return $this;
    }

    public function selectByPoint(IUnlock $unlock): self
    {
        $requestText = '
SELECT
       NULL
FROM
       shutter s
       join unlock u
       on s.id = u.shutter_id 
WHERE
    s.point= :POINT
LIMIT 1
;
   ';
        $request = $this->prepareRequest($requestText);

        $point = $unlock->getPoint();
        $request->bindValue(':POINT', $point);

        $this->processRead($request)->processSuccess();

        return $this;
    }

    private function processRead(\PDOStatement $request): self
    {
        $this->execute($request);

        $data = $this->parseOutput();
        $this->setData($data);

        return $this;
    }

    /**
     * @return Content
     */
    private function parseOutput(): Content
    {
        $result = new DataSet();

        return $result;
    }

    public function selectByShutter(IUnlock $unlock): self
    {
        $requestText = '
SELECT
       NULL
FROM
       unlock u 
WHERE
    u.shutter_id = :SHUTTER_ID
LIMIT 1
;
   ';
        $request = $this->prepareRequest($requestText);

        $shutterId = $unlock->getShutterId();
        $request->bindValue(':SHUTTER_ID', $shutterId);

        $this->processRead($request)->processSuccess();

        return $this;
    }

}
