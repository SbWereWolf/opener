<?php
/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 27.06.18 23:00
 */

use Slim\Http\Request;
use Slim\Http\Response;

class WholeFunctionalTest extends \PHPUnit\Framework\TestCase
{
    private $shutterPoint = '1.1.1.1';
    public function testDismountStorage()
    {
        $body = (new \Slim\Http\RequestBody());
        $link = 'http://local.opener/storage/dismount/';
        $request = $this->setupRequest($body, $link);

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('DELETE');

        /** @var \Slim\App $app */
        $resOut = $this->RunWebCall($request);

        $this->assertEquals(null, $resOut->getBody()->getContents());
        $this->assertEquals(204, $resOut->getStatusCode());
    }

    /**
     * @param string $body
     * @param string $link
     * @return Request
     */
    private function setupRequest(\Psr\Http\Message\StreamInterface $body, string $link): Request
    {
        $env = \Slim\Http\Environment::mock();
        $uri = \Slim\Http\Uri::createFromString($link);
        $headers = \Slim\Http\Headers::createFromEnvironment($env);
        $cookies = [];
        $serverParams = $env->all();
        $uploadedFiles = \Slim\Http\UploadedFile::createFromEnvironment($env);
        $request = new Request('GET', $uri, $headers, $cookies, $serverParams, $body, $uploadedFiles);
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function RunWebCall($request)
    {
        if (!defined('APPLICATION_ROOT')) {
            define('APPLICATION_ROOT', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
        }
        require_once(APPLICATION_ROOT . 'test/test-configuration.php');
        $app = require(APPLICATION_ROOT . 'api/production-configuration.php');

        $resOut = $app->process($request, new Response());
        $resOut->getBody()->rewind();
        return $resOut;
    }

    public function testInstallStorage()
    {
        $body = (new \Slim\Http\RequestBody());
        $link = 'http://local.opener/storage/install/';
        $request = $this->setupRequest($body, $link);

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('POST');

        /** @var \Slim\App $app */
        $resOut = $this->RunWebCall($request);

        $this->assertEquals(null, $resOut->getBody()->getContents());
        $this->assertEquals(201, $resOut->getStatusCode());
    }

    public function testBrowseActual()
    {
        $body = (new \Slim\Http\RequestBody());
        $link = 'http://local.opener/lease/actual/';
        $request = $this->setupRequest($body, $link);

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('GET');

        /** @var \Slim\App $app */
        $resOut = $this->RunWebCall($request);

        $this->assertEquals(json_encode(array([
                'shutter-id' => 1,
                'lease-id' => 0,
                'start' => 0,
                'finish' => 0],
                ['shutter-id' => 2,
                    'lease-id' => 0,
                    'start' => 0,
                    'finish' => 0
                ])
        ), $resOut->getBody()->getContents());
        $this->assertEquals(200, $resOut->getStatusCode());
    }

    public function testRegistrateUser()
    {
        //write request data
        $body = (new \Slim\Http\RequestBody());
        $body->write(json_encode([
            'email' => 'qq@qq.qq',
            'password' => '1',
        ]));
        $link = 'http://local.opener/user/';
        $request = $this->setupRequest($body, $link);

        //set method & content type
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('POST');

        /** @var \Slim\App $app */
        $resOut = $this->RunWebCall($request);

        //Assert
        $this->assertEquals(null, $resOut->getBody()->getContents());
        $this->assertEquals(201, $resOut->getStatusCode());
    }

    public function testOpenSession(): array
    {
        //test log in
        $body = (new \Slim\Http\RequestBody());
        $body->write(json_encode([
            'email' => 'qq@qq.qq',
            'password' => '1',
        ]));
        $link = 'http://local.opener/user/login/';
        $request = $this->setupRequest($body, $link);

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('POST');

        $resOut = $this->RunWebCall($request);

        $output = json_decode($resOut->getBody()->getContents(), true);
        $token = strval($output[0]['token']);

        //Assert
        $this->assertEquals($output[0]['finish'], 0);
        $this->assertTrue(!empty($token));
        $this->assertEquals(201, $resOut->getStatusCode());

        return array('token' => $token);
    }

    /**
     * @depends testOpenSession
     */
    public function testBrowseShutterWithToken(array $previous): array
    {
        $token = $previous['token'];
        //test browse shutter with token
        $body = (new \Slim\Http\RequestBody());

        $link = 'http://local.opener/lease/actual/' . $token . '/';
        $request = $this->setupRequest($body, $link);

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('GET');

        $resOut = $this->RunWebCall($request);

        $firstRow = [
            'shutter-id' => 1,
            'lease-id' => 0,
            'start' => 0,
            'finish' => 0];

        $this->assertEquals(json_encode(array($firstRow,
                ['shutter-id' => 2,
                    'lease-id' => 0,
                    'start' => 0,
                    'finish' => 0
                ])
        ), $resOut->getBody()->getContents());
        $this->assertEquals(200, $resOut->getStatusCode());

        return array('token' => $token,
            'shutter' => $firstRow);
    }

    /**
     * @depends testBrowseShutterWithToken
     */
    public function testTakeALease(array $previous)
    {
        $token = $previous['token'];
        $dataSet = $previous['shutter'];
        $body = (new \Slim\Http\RequestBody());
        $body->write(json_encode([
            'token' => $token,
            'user-id' => 0,
            'shutter-id' => $dataSet['shutter-id'],
            'start' => $dataSet['start'],
            'finish' => $dataSet['finish'],
            'occupancy-type-id' => 0,
        ]));

        $link = 'http://local.opener/lease/';
        $request = $this->setupRequest($body, $link);

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('POST');

        $resOut = $this->RunWebCall($request);

        $this->assertEquals(json_encode(array()), $resOut->getBody()->getContents());
        $this->assertEquals(201, $resOut->getStatusCode());

        return array('token' => $token,);
    }

    /**
     * @depends testOpenSession
     */
    public function testBrowseOwnLease(array $previous): array
    {
        $token = $previous['token'];
        //test browse own lease
        $body = (new \Slim\Http\RequestBody());

        $link = 'http://local.opener/lease/current/' . $token . '/';
        $request = $this->setupRequest($body, $link);

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('GET');

        $resOut = $this->RunWebCall($request);

        $output = json_decode($resOut->getBody()->getContents(), true)[0];

        //Assert
        $this->assertEquals($output['shutter-id'], 0);
        $this->assertEquals($output['lease-id'], 1);
        $this->assertTrue($output['start'] > 0);
        $this->assertTrue($output['finish'] > $output['start']);
        $this->assertEquals(200, $resOut->getStatusCode());

        return array('token' => $token,
            'lease' => $output['lease-id']);
    }

    /**
     * @depends testBrowseOwnLease
     */
    public function testRequestUnlocking(array $previous)
    {
        $token = $previous['token'];
        $leaseId = $previous['lease'];
        //test request unlocking
        $body = (new \Slim\Http\RequestBody());
        $body->write(json_encode([
            'token' => $token,
            'lease-id' => $leaseId,
        ]));

        $link = 'http://local.opener/unlock/';
        $request = $this->setupRequest($body, $link);

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('POST');

        $resOut = $this->RunWebCall($request);

        $this->assertEquals(null, $resOut->getBody()->getContents());
        $this->assertEquals(201, $resOut->getStatusCode());
    }

    public function testGetUnlockingTask()
    {
        //test get unlocking task
        $body = (new \Slim\Http\RequestBody());

        $link = "http://local.opener/unlock/$this->shutterPoint/";
        $request = $this->setupRequest($body, $link);

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('GET');

        $resOut = $this->RunWebCall($request);

        //Assert
        $this->assertEquals(null, $resOut->getBody()->getContents());
        $this->assertEquals(200, $resOut->getStatusCode());
    }

    public function testReportCompleteUnlockingTask()
    {
        //test report complete unlocking task
        $body = (new \Slim\Http\RequestBody());

        $link = "http://local.opener/unlock/$this->shutterPoint/";
        $request = $this->setupRequest($body, $link);

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('DELETE');

        $resOut = $this->RunWebCall($request);

        //Assert
        $this->assertEquals(null, $resOut->getBody()->getContents());
        $this->assertEquals(204, $resOut->getStatusCode());
    }

    /**
     * @depends testOpenSession
     */
    public function testCloseSession(array $previous)
    {
        $token = $previous['token'];
        //test close session
        $body = (new \Slim\Http\RequestBody());

        $link = 'http://local.opener/session/' . $token . '/';
        $request = $this->setupRequest($body, $link);

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('DELETE');

        $resOut = $this->RunWebCall($request);

        //Assert
        $this->assertEquals(null, $resOut->getBody()->getContents());
        $this->assertEquals(204, $resOut->getStatusCode());
    }

    /**
     * @depends testBrowseOwnLease
     */
    public function testUpdateLease(array $previous)
    {
        $leaseId = $previous['lease'];
        //write request data
        $body = (new \Slim\Http\RequestBody());
        $body->write(json_encode([
            'id' => $leaseId,
            'user-id' => 1,
            'shutter-id' => 1,
            'start' => 1,
            'finish' => 2,
            'occupancy-type-id' => 1,
        ]));
        $link = 'http://local.opener/lease/';
        $request = $this->setupRequest($body, $link);

        //set method & content type
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withMethod('PUT');

        $resOut = $this->RunWebCall($request);

        //Assert
        $this->assertEquals(null, $resOut->getBody()->getContents());
        $this->assertEquals(200, $resOut->getStatusCode());
    }
}
