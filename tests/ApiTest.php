<?php

namespace LaravelApi\Tests;


use LaravelApi\Endpoints\Endpoint;
use LaravelApi\Endpoints\Operation;
use LaravelApi\Endpoints\ResourceEndpoint;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Routing\Router;


class ApiTest extends TestCase
{
	
	/**
	 * @var Router
	 */
	protected $router;
	
	
	public function setUp ()
	{
		parent::setUp ();
		
		$this->router = $this->app->make ( Registrar::class );
	}
	
	
	public function testBaseEndpointRegistration ()
	{
		$methods = [ 'get', 'post', 'put', 'delete', 'patch', 'options' ];
		
		foreach ( $methods as $method )
		{
			$operation = $this->api->$method ( 'uri', 'Controller@action' );
			$this->assertInstanceOf ( Operation::class, $operation );
		}
		
		$this->assertInstanceOf ( Endpoint::class, $this->api->getEndpointByUri ( 'uri' ) );
	}
	
	
	public function testCustomEndpointRegistration ()
	{
		$endpoint = $this->api->getEndpointByUri ( 'foobar' );
		
		$this->assertInstanceOf ( Endpoint::class, $endpoint );
		$this->assertInstanceOf ( Operation::class, $endpoint->setMethod ( 'post' ) );
	}
	
	
	public function testAggregateEndpointRegistration ()
	{
		$operation = $this->api->aggregate ( 'aggregate-uri', [ 'App\\Page', 'App\\User' ] );
		
		$this->assertInstanceOf ( Operation::class, $operation );
	}
	
	
	public function testResourceEndpointRegistration ()
	{
		$endpoint = $this->api->resource ( 'users', 'Controller@action' );
		
		$this->assertInstanceOf ( ResourceEndpoint::class, $endpoint );
		$this->assertInstanceOf ( ResourceEndpoint::class, $endpoint->setApi ( $this->api ) );
	}
	
	
	public function testVersion ()
	{
		$this->api->version ( 'v1', function () {
			$operation = $this->api->get ( 'uri', 'Controller@action' );
			$this->assertEquals ( 'v1', $operation->getPrefix () );
			$this->assertTrue ( in_array ( 'v1', $operation->getTags () ) );
		} );
	}
	
	
	public function testTitle ()
	{
		$this->assertInternalType ( 'string', $this->api->title () );
	}
	
	
	public function testJsonSerialization ()
	{
		$this->assertTrue ( is_array ( $this->api->jsonSerialize () ) );
	}
	
	
}