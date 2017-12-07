<?php

namespace Finnegan\Api\Tests;


use Finnegan\Api\Api;
use Orchestra\Testbench\TestCase as OrchestraTestCase;


class TestCase extends OrchestraTestCase
{
	
	/**
	 * @var Api
	 */
	protected $api;
	
	
	public function setUp ()
	{
		parent::setUp ();
		$this->api = $this->app->make ( Api::class );
	}
	
	
}