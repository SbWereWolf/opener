<?php

namespace DataStorage\Lease;


use BusinessLogic\Lease\ILease;

class LeaseAccessSqlile extends CommonLeaseAccess implements LeaseAccess
{
    public function getShutterId(ILease $lease): LeaseAccess
    {
        $requestText = '
SELECT
    l.shutter_id as shutter_id
FROM
     lease l
     JOIN session s
     on l.user_id = s.user_id
WHERE 
    l.id = :ID
    AND s.token = :TOKEN
LIMIT 1
;
   ';
        $request = $this->prepareRequest($requestText);

        $id = $lease->getId();
        $token = $lease->getToken();

        $request->bindValue(':ID', $id, \PDO::PARAM_INT);
        $request->bindValue(':TOKEN', $token, \PDO::PARAM_STR);

        $this->processForOutput($request)->processSuccess();

        return $this;
    }

    public function findFreeHours(ILease $lease): LeaseAccess
    {
        $requestText = '
SELECT
    NULL AS is_free
WHERE
EXISTS
    (
  SELECT
      NULL
  FROM
       renting
  WHERE
      shutter_id = :SHUTTER_ID
    )
  AND NOT EXISTS (
            SELECT
                NULL
            FROM
                 lease
            WHERE
                (:ID = 0 OR id <> :ID)
                AND shutter_id = :SHUTTER_ID
                AND (start < strftime("%s", "now") AND finish > strftime("%s", "now"))
                AND (start < strftime("%s", "now")+60*30 AND finish > strftime("%s", "now")+60*30)
    )
LIMIT 1
;
   ';
        $request = $this->prepareRequest($requestText);

        $id = $lease->getId();
        $shutterId = $lease->getShutterId();

        $request->bindValue(':ID', $id, \PDO::PARAM_INT);
        $request->bindValue(':SHUTTER_ID', $shutterId, \PDO::PARAM_INT);

        $this->processForOutput($request)->processSuccess();

        return $this;
    }

    public function getActual(): LeaseAccess
    {
        $requestText = '
SELECT
       ro.shutter_id AS shutter_id
FROM
     renting ro
WHERE
    NOT EXISTS (
          SELECT
              NULL
          FROM
               renting ri
               join lease li
               on li.shutter_id = ri.shutter_id
          WHERE
              ro.id = ri.id
            AND li.start < strftime("%s", "now")
            AND strftime("%s", "now") < li.finish
        )
;
   ';
        $request = $this->prepareRequest($requestText);
        $this->processForOutput($request)->processSuccess();

        return $this;
    }

    public function insert(ILease $lease): LeaseAccess
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
    (select MAX(user_id) from session WHERE token = :TOKEN),
    :SHUTTER_ID,
    strftime("%s", "now"),
    strftime("%s", "now")+60*30,
    (select id from occupancy_type where code = "BUSY")
)
;
   ';
        $request = $this->prepareRequest($requestText);

        $token = $lease->getToken();
        $shutterId = $lease->getShutterId();

        $request->bindValue(':TOKEN', $token, \PDO::PARAM_STR);
        $request->bindValue(':SHUTTER_ID', $shutterId, \PDO::PARAM_INT);

        $this->processWrite($request)->processSuccess();

        return $this;
    }

    public function update(ILease $lease): LeaseAccess
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
 AND :START < :FINISH
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

        $this->processWrite($request)->processSuccess();

        return $this;
    }

    public function getCurrent(ILease $lease): LeaseAccess
    {
        $requestText = '
SELECT
       lo.id AS id,
       lo.start AS start,
       lo.finish AS finish
FROM
       lease lo
WHERE
    lo.id IN (
    SELECT
        li.id 
    FROM
        session si
        join lease li
        on li.user_id = si.user_id
    WHERE        
            si.token = :TOKEN
        AND si.finish > strftime("%s","now")
        AND li.finish > strftime("%s","now")          
        AND li.start < strftime("%s","now")
    ORDER BY li.id
    )
;
   ';
        $request = $this->prepareRequest($requestText);

        $token = $lease->getToken();
        $request->bindValue(':TOKEN', $token);

        $this->processForOutput($request)->processSuccess();

        return $this;
    }
}
