<?php

namespace Environment\Storage;


use Slim\Http\Response;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Controller extends \Environment\Controller
{
    const INSTALL = '
CREATE TABLE IF NOT EXISTS occupancy_type
(
    id INTEGER
        primary key
         autoincrement,
    code TEXT
)
;

CREATE UNIQUE INDEX IF NOT EXISTS occupancy_type_code_uindex
    on occupancy_type (code)
;

CREATE TABLE IF NOT EXISTS shutter
(
    id INTEGER
        primary key
         autoincrement,
    remark TEXT,
    point TEXT
)
;

CREATE TABLE IF NOT EXISTS renting
(
    id INTEGER
        primary key
         autoincrement,
    shutter_id INTEGER
        constraint renting_shutter_id_fk
            references shutter
)
;

CREATE UNIQUE INDEX IF NOT EXISTS renting_shutter_id_uindex
    on renting (shutter_id)
;

CREATE INDEX IF NOT EXISTS shutter_point_id_index
    on shutter (point, id)
;

CREATE UNIQUE INDEX IF NOT EXISTS shutter_point_uindex
    on shutter (point)
;

CREATE TABLE IF NOT EXISTS unlock
(
    shutter_id INTEGER
        constraint unlock_shutter_id_fk
            references shutter
)
;

CREATE UNIQUE INDEX IF NOT EXISTS unlock_shutter_id_uindex
    on unlock (shutter_id)
;

CREATE TABLE IF NOT EXISTS "user"
(
    id INTEGER
        primary key
         autoincrement,
    email TEXT,
    secret TEXT
)
;

CREATE TABLE IF NOT EXISTS lease
(
    id INTEGER
        primary key
         autoincrement,
    user_id INTEGER
        constraint occupancy_user_id_fk
            references "user",
    shutter_id INTEGER
        constraint occupancy_shutter_id_fk
            references shutter,
    start INTEGER,
    finish INTEGER,
    occupancy_type_id INTEGER
        constraint occupancy_occupancy_type_id_fk
            references occupancy_type
)
;

CREATE INDEX IF NOT EXISTS occupancy_user_id_start_finish_shutter_id_index
    on lease (user_id, start, finish, shutter_id)
;

CREATE TABLE IF NOT EXISTS session
(
    id INTEGER
        primary key
         autoincrement,
    token TEXT,
    finish INTEGER,
    user_id INTEGER
        constraint session_user_id_fk
            references "user"
)
;

CREATE INDEX IF NOT EXISTS session_finish_token_user_id_index
    on session (finish, token, user_id)
;

CREATE INDEX IF NOT EXISTS user_email_secret_index
    on "user" (email, secret)
;

CREATE UNIQUE INDEX IF NOT EXISTS user_email_uindex
    on "user" (email)
;
'.'
INSERT INTO occupancy_type (code) VALUES (\'BUSY\');
INSERT INTO occupancy_type (code) VALUES (\'OCCUPIED\');
INSERT INTO shutter (point) VALUES (\'1.1.1.1\');
INSERT INTO shutter (point) VALUES (\'street-red-building-33\');
INSERT INTO shutter (point) VALUES (\'some-where\');
INSERT INTO renting (shutter_id) SELECT id FROM shutter LIMIT 1;
INSERT INTO renting (shutter_id) SELECT id FROM shutter OFFSET 2 LIMIT 1;
    ';
    const UNMOUNT = '
DELETE FROM renting;
DELETE FROM lease;
DELETE FROM occupancy_type;
DELETE FROM session;
DELETE FROM unlock;
DELETE FROM shutter;
DELETE FROM "user";

drop table IF EXISTS renting;
drop table IF EXISTS lease;
drop table IF EXISTS occupancy_type;
drop table IF EXISTS session;
drop table IF EXISTS unlock;
drop table IF EXISTS shutter;
drop table IF EXISTS "user";

drop index IF EXISTS renting_shutter_id_uindex;
drop index IF EXISTS occupancy_type_code_uindex;
drop index IF EXISTS shutter_point_id_index;
drop index IF EXISTS shutter_point_uindex;
drop index IF EXISTS unlock_shutter_id_uindex;
drop index IF EXISTS user_email_secret_index;
drop index IF EXISTS user_email_uindex;
drop index IF EXISTS occupancy_user_id_start_finish_shutter_id_index;
drop index IF EXISTS session_finish_token_user_id_index;

VACUUM;
';

    public function process(): Response
    {
        $request = $this->getRequest();

        $isPost = $request->isPost();
        if ($isPost) {
            $response = $this->create();
        }
        $isDelete = $request->isDelete();
        if ($isDelete) {
            $response = $this->delete();
        }

        return $response;
    }

    /**
     * @return Response
     */
    private function create(): Response
    {
        $response = $this->executeCommand(self::INSTALL);

        return $response;
    }

    /**
     * @param $requestText
     * @return Response
     */
    private function executeCommand($requestText)
    {
        $dataPath = $this->getDataPath();
        $db = (new \PDO("sqlite:$dataPath"));

        $isSuccess = $db->exec($requestText) !== false;

        $response = $this->getResponse();
        if ($isSuccess) {
            $response = $response->withStatus(200);
        }
        if (!$isSuccess) {
            $response = $response->withStatus(500);
        }
        return $response;
    }

    /**
     * @return Response
     */
    private function delete(): Response
    {
        $response = $this->executeCommand(self::UNMOUNT);

        return $response;
    }
}
