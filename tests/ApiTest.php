<?php

namespace Finnegan\Api\Tests;


use Finnegan\Api\Api;
use Finnegan\Api\ApiEndpoint;


class ApiTest extends TestCase
{
	
	
	public function testModelRegistration ()
	{
		$model = 'App\\Page';
		
		$this->assertInstanceOf ( Api::class, $this->api->models ( $model ) );
		
		$this->assertContains ( $model, $this->api->getModels () );
		
		$this->assertTrue ( $this->api->isWhitelisted ( 'App\\Page' ) );
		$this->assertFalse ( $this->api->isWhitelisted ( 'App\\Post' ) );
	}
	
	
	public function testEndpointRegistration ()
	{
		$endpoint = $this->api->endpoint ( 'uri', function () {
		} );
		
		$this->assertInstanceOf ( ApiEndpoint::class, $endpoint );
	}
}