<?php

namespace LaravelApi\Tests;


use LaravelApi\ApiServer;
use Orchestra\Testbench\TestCase as OrchestraTestCase;


class TestCase extends OrchestraTestCase
{
	
	/**
	 * @var ApiServer
	 */
	protected $api;
	
	
	public function setUp ()
	{
		parent::setUp ();
		$this->api = $this->app->make ( ApiServer::class );
	}
	
	
}