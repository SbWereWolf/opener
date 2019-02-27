<?php

namespace Environment\Storage;


use DataStorage\Basis\DataSource;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Controller extends \Environment\Basis\Controller
{
    private $install = '';
    private $unmount = '';

    const INSTALL_SQLIGHT = "
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

CREATE TABLE IF NOT EXISTS 'user'
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
    references 'user',
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
    references 'user'
)
;

CREATE INDEX IF NOT EXISTS session_finish_token_user_id_index
  on session (finish, token, user_id)
;

CREATE INDEX IF NOT EXISTS user_email_secret_index
  on 'user' (email, secret)
;

CREATE UNIQUE INDEX IF NOT EXISTS user_email_uindex
  on 'user' (email)
;

INSERT INTO occupancy_type (code) VALUES ('BUSY');
INSERT INTO occupancy_type (code) VALUES ('OCCUPIED');
INSERT INTO shutter (point) VALUES ('1.1.1.1');
INSERT INTO shutter (point) VALUES ('street-red-building-33');
INSERT INTO shutter (point) VALUES ('some-where');
INSERT INTO renting (shutter_id) SELECT id FROM shutter LIMIT 1;
INSERT INTO renting (shutter_id) SELECT id FROM shutter  LIMIT 1 OFFSET 2
;
    ";
    const UNMOUNT_SQLIGHT = '
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

    const INSTALL_MYSQL = "
CREATE TABLE occupancy_type
(
  id int PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(768)
);
CREATE UNIQUE INDEX occupancy_type_code_uindex ON occupancy_type (code);

CREATE TABLE shutter
(
  id int PRIMARY KEY AUTO_INCREMENT,
  remark VARCHAR(4000),
  point VARCHAR(95)
);
CREATE UNIQUE INDEX shutter_point_uindex ON shutter (point);

CREATE TABLE renting
(
  id int PRIMARY KEY AUTO_INCREMENT,
  shutter_id int,
  CONSTRAINT table_name_shutter_id_fk FOREIGN KEY (shutter_id) REFERENCES shutter (id)
);
CREATE UNIQUE INDEX renting_shutter_id_uindex ON renting (shutter_id);

CREATE TABLE unlocking
(
  shutter_id int,
  CONSTRAINT unlocking_shutter_id_fk FOREIGN KEY (shutter_id) REFERENCES shutter (id)
);
CREATE UNIQUE INDEX unlocking_shutter_id_uindex ON unlocking (shutter_id);

CREATE TABLE person
(
  id int PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255),
  secret VARCHAR(511)
);
CREATE UNIQUE INDEX person_email_uindex ON person (email);

CREATE INDEX person_email_secret_index
on person (email, secret)
;

CREATE TABLE lease
(
  id int PRIMARY KEY AUTO_INCREMENT,
  person_id int,
  shutter_id int,
  start int,
  finish int,
  occupancy_type_id int,
  CONSTRAINT lease_person_id_fk FOREIGN KEY (person_id) REFERENCES person (id),
  CONSTRAINT lease_shutter_id_fk FOREIGN KEY (shutter_id) REFERENCES shutter (id),
  CONSTRAINT lease_occupancy_type_id_fk FOREIGN KEY (occupancy_type_id) REFERENCES occupancy_type (id)
);
CREATE INDEX lease_person_id_index ON lease (person_id);
CREATE INDEX lease_shutter_id_index ON lease (shutter_id);
CREATE INDEX lease_occupancy_type_id_index ON lease (occupancy_type_id);

CREATE TABLE session
(
  id int PRIMARY KEY AUTO_INCREMENT,
  token varchar(755),
  finish int,
  person_id int,
  CONSTRAINT session_person_id_fk FOREIGN KEY (person_id) REFERENCES person (id)
);
CREATE INDEX session_token_person_id_finish_index ON session (token, person_id, finish DESC);

INSERT INTO occupancy_type (code) VALUES ('BUSY');
INSERT INTO occupancy_type (code) VALUES ('OCCUPIED');
INSERT INTO shutter (point) VALUES ('1.1.1.1');
INSERT INTO shutter (point) VALUES ('street-red-building-33');
INSERT INTO shutter (point) VALUES ('some-where');
INSERT INTO renting (shutter_id) SELECT id FROM shutter LIMIT 1;
INSERT INTO renting (shutter_id) SELECT id FROM shutter  LIMIT 1 OFFSET 2
;
    ";
    const UNMOUNT_MYSQL = '
drop table lease;
drop table occupancy_type;
drop table renting;
drop table session;
drop table person;
drop table unlocking;
drop table shutter;
';

    public function __construct(Request $request, Response $response, array $parametersInPath, DataSource $dataPath)
    {
        parent::__construct($request, $response, $parametersInPath, $dataPath);

        switch (DBMS) {
            case SQLITE:
                $this->setInstall(self::INSTALL_SQLIGHT);
                $this->setUnmount(self::UNMOUNT_SQLIGHT);
                break;
            case MYSQL:
                $this->setInstall(self::INSTALL_MYSQL);
                $this->setUnmount(self::UNMOUNT_MYSQL);
                break;
        }
    }

    public function process(): Response
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

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
        $response = $this->executeCommand(self::INSTALL_SQLIGHT);

        return $response;
    }

    /**
     * @param $requestText
     * @return Response
     */
    private function executeCommand($requestText)
    {
        $dataSource = $this->getDataPath();
        $db = new \PDO(
            $dataSource->getDsn(),
            $dataSource->getUsername(),
            $dataSource->getPasswd(),
            $dataSource->getOptions());

        $isSuccess = $db->exec($requestText) !== false;

        $isDelete = $this->getRequest()->isDelete();
        $isPost = $this->getRequest()->isPost();
        $response = $this->getResponse();
        if ($isSuccess && $isPost) {
            $response = $response->withStatus(201);
        }
        if ($isSuccess && $isDelete) {
            $response = $response->withStatus(204);
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
        $response = $this->executeCommand(self::UNMOUNT_SQLIGHT);

        return $response;
    }

    /**
     * @param string $install
     * @return Controller
     */
    private function setInstall(string $install): Controller
    {
        $this->install = $install;
        return $this;
    }

    /**
     * @return string
     */
    private function getInstall(): string
    {
        return $this->install;
    }

    /**
     * @param string $unmount
     * @return Controller
     */
    private function setUnmount(string $unmount): Controller
    {
        $this->unmount = $unmount;
        return $this;
    }

    /**
     * @return string
     */
    private function getUnmount(): string
    {
        return $this->unmount;
    }
}
