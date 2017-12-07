<?php

namespace Finnegan\Api\Tests;


class ApiEndpointTest extends TestCase
{
	
	
	public function testEndpointConfig ()
	{
		$endpoint = $this->api->endpoint ( 'uri', function () {
		}, 'post' );
		
		$this->assertEquals ( 'uri', $endpoint->uri );
		$this->assertTrue ( is_callable ( $endpoint->action ) );
		$this->assertEquals ( 'post', $endpoint->methods );
	}
	
	
	public function testEndpointMetadata ()
	{
		$endpoint = $this->api
			->endpoint ( 'uri', function () {
			} )
			->description ( 'Test description' )
			->parameters ( [
							   [ 'foo', 'bar', 'foobar' ],
							   [ 'foo', 'bar', 'foobar' ]
						   ] )
			->examples ( [
							 [ 'foo', 'bar' ]
						 ] );
		
		$this->assertEquals ( 'Test description', $endpoint->description );
		
		$this->assertInternalType ( 'array', $endpoint->parameters );
		$this->assertEquals ( 2, count ( $endpoint->parameters ) );
		$this->assertEquals ( 3, count ( $endpoint->parameters[ 0 ] ) );
		
		$this->assertInternalType ( 'array', $endpoint->examples );
		$this->assertEquals ( 1, count ( $endpoint->examples ) );
		$this->assertEquals ( 2, count ( $endpoint->examples[ 0 ] ) );
	}
}