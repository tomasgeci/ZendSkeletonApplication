<?php

namespace AlbumTest\Controller;

use AlbumTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Album\Controller\AlbumController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class AlbumControllerTest extends \PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new AlbumController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'album'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    // -tge- first testcase - should be 200
    public function testAlbumActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'index');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    // -tge- first testcase - in edit without id should be redirected with 302
    public function testAlbumEditCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'edit');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    // -tge- first testcase - in add without id should be 200
    public function testAlbumAddCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'add');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    // -tge- first testcase - in edit cannot be non-existing ID - should be redirected
    public function testAlbumEditNonExistingAlbums()
    {
        $this->routeMatch->setParam('action', 'edit');
        $this->routeMatch->setParam('id', 12345678); // pass non-existing ID

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    // -tge- first testcase - in delete cannot be non-existing ID - should be redirected
    public function testAlbumDeleteNonExistingAlbums()
    {
        $this->routeMatch->setParam('action', 'del');
        $this->routeMatch->setParam('id', 12345678); // pass non-existing ID

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }

}