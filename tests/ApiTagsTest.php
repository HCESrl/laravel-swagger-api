<?php

namespace LaravelApi\Tests;


use Calcinai\Strut\Definitions\Tag;
use LaravelApi\ApiServer;


class ApiTagsTest extends TestCase
{
	
	
	public function testTags ()
	{
		$tag = $this->api->tag ( 'foobar', 'Tag description', function () {
			$operation = $this->api->get ( 'tagged-uri', 'Controller@action' );
			$this->assertTrue ( in_array ( 'foobar', $operation->getTags () ) );
		} );
		
		$this->assertInstanceOf ( Tag::class, $tag );
		$this->assertEquals ( 'foobar', $tag->getName () );
		$this->assertEquals ( 'Tag description', $tag->getDescription () );
		
		$api = $this->api->tags ( [
									  'tag_1' => 'Some tag description',
									  'tag_2' => 'Some tag description',
								  ] );
		$this->assertInstanceOf ( ApiServer::class, $api );
	}
	
	
}