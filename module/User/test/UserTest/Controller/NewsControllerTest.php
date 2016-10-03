<?php

namespace UserTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class NewsControllerTest extends AbstractHttpControllerTestCase
{
    private $serviceManager;
    private $url;

    public function setUp()
    {
        parent::setUp();
        $this->setApplicationConfig(include __DIR__ . '/../../../../../config/application.config.php');

        $this->serviceManager = $this->getApplication()->getServiceManager();
        $this->url            = '/news';
    }

    public function testGetList()
    {
        $this->dispatch($this->url);
        $this->assertResponseStatusCode(200);
    }

    public function testCreate()
    {
        $this->dispatch($this->url, 'POST', array(
            'message' => 'Test 1: Lorem ipsum dolor sit asimet',
            'user'    => 1
        ));
        $this->assertResponseStatusCode(201);

        $this->dispatch($this->url, 'POST', array(
            'message' => 'Test 2: Lorem ipsum dolor sit asimet',
            'user'    => 1
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
        $url = $this->url . "/1";
        $this->dispatch($url, 'PUT', array(
            'message' => '[UPDATED] User2: Lorem ipsum dolor sit asimet',
            'user'    => 2
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
