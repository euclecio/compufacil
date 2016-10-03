<?php

namespace UserTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class UserControllerTest extends AbstractHttpControllerTestCase
{
    private $serviceManager;
    private $url;

    public function setUp()
    {
        parent::setUp();
        $this->setApplicationConfig(include __DIR__ . '/../../../../../config/application.config.php');

        $this->serviceManager = $this->getApplication()->getServiceManager();
        $this->url            = '/user';
    }

    public function testGetList()
    {
        $this->dispatch($this->url);
        $this->assertResponseStatusCode(200);
    }

    public function testCreate()
    {
        $this->dispatch($this->url, 'POST', array(
            'email'    => 'test.user1@example.com',
            'name'     => 'Test User One',
            'password' => '123456',
            'status'   => 1 /* Available */
        ));
        $this->assertResponseStatusCode(201);

        $this->dispatch($this->url, 'POST', array(
            'email'    => 'test.user2@example.com',
            'name'     => 'Test User Two',
            'password' => '123456',
            'status'   => 1 /* Available */
        ));
        $this->assertResponseStatusCode(201);
    }

    public function testGet()
    {
        $url = $this->url . "/1";
        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);
    }

    public function testUpdate()
    {
        $url = $this->url . "/6";
        $this->dispatch($url, 'PUT', array(
            "email"    => "test.user.one@example.com",
            "name"     => "Test User One (Updated)",
            "password" => "654321",
            "status"   => 3 /* Busy */
        ));
        $this->assertResponseStatusCode(200);
    }

    public function testPatch()
    {
        $url = $this->url . "/1";
        $this->dispatch($url, 'PATCH', array(
            'status' => 2 /* Away */
        ));
        $this->assertResponseStatusCode(200);
    }

    public function testDelete()
    {
       $url = $this->url . "/3";
       $this->dispatch($url, 'DELETE');
       $this->assertResponseStatusCode(204);
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}
