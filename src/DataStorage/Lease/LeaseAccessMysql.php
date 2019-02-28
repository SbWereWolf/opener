<?php

namespace DataStorage\Lease;


use BusinessLogic\Lease\ILease;

class LeaseAccessMysql extends CommonLeaseAccess implements LeaseAccess
{
    public function getShutterId(ILease $lease): LeaseAccess
    {
        $requestText = '
SELECT
       l.shutter_id as shutter_id
FROM
     lease l
       JOIN session s
         on l.person_id = s.person_id
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
            AND li.start < UNIX_TIMESTAMP()
            AND UNIX_TIMESTAMP() < li.finish
        )
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
            AND li.start < UNIX_TIMESTAMP()
            AND UNIX_TIMESTAMP() < li.finish
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
        person_id,
        shutter_id,
        start,
        finish,
        occupancy_type_id
        )
VALUES(
          (select MAX(person_id) from session WHERE token = :TOKEN),
          :SHUTTER_ID,
          UNIX_TIMESTAMP(),
          UNIX_TIMESTAMP()+60*30,
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
    person_id = :USER_ID,
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
                      on li.person_id = si.person_id
             WHERE
                 si.token = :TOKEN
               AND si.finish > UNIX_TIMESTAMP()
               AND li.finish > UNIX_TIMESTAMP()
               AND li.start < UNIX_TIMESTAMP()
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
