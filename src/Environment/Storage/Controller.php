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
  id   INTEGER
    primary key
  autoincrement,
  code TEXT
);

create unique index IF NOT EXISTS occupancy_type_code_uindex
  on occupancy_type (code);

CREATE TABLE IF NOT EXISTS role
(
  id   INTEGER
    primary key
  autoincrement,
  code TEXT not null
);

create unique index IF NOT EXISTS role_code_uindex
  on role (code);

CREATE TABLE IF NOT EXISTS shutter
(
  id     INTEGER
    primary key
  autoincrement,
  remark TEXT,
  point  TEXT
);

create index IF NOT EXISTS shutter_point_id_index
  on shutter (point, id);

create unique index IF NOT EXISTS shutter_point_uindex
  on shutter (point);

CREATE TABLE IF NOT EXISTS unlock
(
  shutter_id INTEGER
    constraint unlock_shutter_id_fk
    references shutter
);

create unique index IF NOT EXISTS unlock_shutter_id_uindex
  on unlock (shutter_id);

CREATE TABLE IF NOT EXISTS "user"
(
  id     INTEGER
    primary key
  autoincrement,
  email  TEXT,
  secret TEXT
);

create index IF NOT EXISTS user_email_secret_index
  on "user" (email, secret);

create unique index IF NOT EXISTS user_email_uindex
  on "user" (email);

CREATE TABLE IF NOT EXISTS lease
(
  id                INTEGER
    primary key
  autoincrement,
  user_id           INTEGER
    constraint occupancy_user_id_fk
    references "user",
  shutter_id        INTEGER
    constraint occupancy_shutter_id_fk
    references shutter,
  start             INTEGER,
  finish            INTEGER,
  occupancy_type_id INTEGER
    constraint occupancy_occupancy_type_id_fk
    references occupancy_type
);

create index IF NOT EXISTS occupancy_user_id_start_finish_shutter_id_index
  on lease (user_id, start, finish, shutter_id);

CREATE TABLE IF NOT EXISTS session
(
  id      INTEGER
    primary key
  autoincrement,
  token   TEXT,
  finish  INTEGER,
  user_id INTEGER
    constraint session_user_id_fk
    references "user"
);

create index IF NOT EXISTS session_finish_token_user_id_index
  on session (finish, token, user_id);

CREATE TABLE IF NOT EXISTS user_role
(
  id      INTEGER
    primary key
  autoincrement,
  user_id INTEGER
    constraint user_role_user_id_fk
    references "user",
  role_id INTEGER
    constraint user_role_role_id_fk
    references role
);

create unique index IF NOT EXISTS user_role_user_id_role_id_uindex
  on user_role (user_id, role_id);
  
CREATE TABLE IF NOT EXISTS renting
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    shutter_id INTEGER,
    CONSTRAINT renting_shutter_id_fk FOREIGN KEY (shutter_id) REFERENCES shutter (id)
);
CREATE UNIQUE INDEX IF NOT EXISTS renting_shutter_id_uindex ON renting (shutter_id);
    ';
    const UNMOUNT = '
drop table IF EXISTS renting;
drop table IF EXISTS lease;
drop table IF EXISTS occupancy_type;
drop table IF EXISTS session;
drop table IF EXISTS unlock;
drop table IF EXISTS shutter;
drop table IF EXISTS user_role;
drop table IF EXISTS role;
drop table IF EXISTS "user";

drop index IF EXISTS renting_shutter_id_uindex;
drop index IF EXISTS occupancy_type_code_uindex;
drop index IF EXISTS role_code_uindex;
drop index IF EXISTS shutter_point_id_index;
drop index IF EXISTS shutter_point_uindex;
drop index IF EXISTS unlock_shutter_id_uindex;
drop index IF EXISTS user_email_secret_index;
drop index IF EXISTS user_email_uindex;
drop index IF EXISTS occupancy_user_id_start_finish_shutter_id_index;
drop index IF EXISTS session_finish_token_user_id_index;
drop index IF EXISTS user_role_user_id_role_id_uindex;
';

    public function process(): Response
    {
        $method = $this->getRequest()->getMethod();
        $response = $this->getResponse();
        switch ($method) {
            case self::POST:
                $response = $this->create();
                break;
            case self::DELETE:
                $response = $this->delete();
                break;
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
