<?php

namespace Finnegan\Api\Tests;


use Finnegan\Api\Endpoints\AggregateEndpoint;
use Finnegan\Api\Endpoints\BaseEndpoint;
use Finnegan\Api\Endpoints\EndpointInterface;
use Finnegan\Api\Endpoints\ModelsEndpoint;


class ApiTest extends TestCase
{
	
	
	public function testBaseEndpointRegistration ()
	{
		$endpoint = $this->api->endpoint ( 'uri', function () {
		} );
		
		$this->assertInstanceOf ( BaseEndpoint::class, $endpoint );
		
		$this->assertInstanceOf ( BaseEndpoint::class, array_first ( $endpoints ) );
		$this->assertInstanceOf ( EndpointInterface::class, array_first ( $endpoints ) );
	}
	
	
	public function testAggregateEndpointRegistration ()
	{
		$endpoint = $this->api->aggregate ( 'aggregate-uri', [ 'App\\Page', 'App\\User' ] );
		
		$this->assertInstanceOf ( AggregateEndpoint::class, $endpoint );
		
		$this->assertInternalType ( 'array', $endpoint->resources );
		$this->assertEquals ( 2, count ( $endpoint->resources ) );
	}
	
	
	public function testModelRegistration ()
	{
		$model = 'App\\Page';
		
		$this->assertInstanceOf ( ModelsEndpoint::class, $this->api->models ( $model ) );
		
		$this->assertTrue ( $this->app->resolved ( ModelsEndpoint::class ) );
	}
}