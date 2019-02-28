<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 5:24
 */

namespace DataStorage\Unlock;


use BusinessLogic\Unlock\IUnlock;

class UnlockAccessSqlite extends CommonUnlockAccess implements UnlockAccess
{
    public function insert(IUnlock $unlock): UnlockAccess
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

        $this->processWrite($request)->processSuccess();

        return $this;
    }

    public function delete(IUnlock $unlock): UnlockAccess
    {
        $requestText = '
DELETE
FROM
    unlock
WHERE 
    EXISTS( 
    SELECT 
        NULL 
    FROM 
        shutter s 
        join unlock u2 on s.id = u2.shutter_id 
    WHERE 
        u2.shutter_id = shutter_id 
        AND s.point = :POINT
    )
;
   ';
        $request = $this->prepareRequest($requestText);

        /* TODO : every where to apply this process */
        $isSuccess = $request !== false;
        if ($isSuccess) {
            $point = $unlock->getPoint();
            $request->bindValue(':POINT', $point, \PDO::PARAM_STR);

            $this->processWrite($request)->processSuccess();
        }

        return $this;
    }

    public function selectByPoint(IUnlock $unlock): UnlockAccess
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

    public function selectByShutter(IUnlock $unlock): UnlockAccess
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
