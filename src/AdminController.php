<?php

namespace Finnegan\Api;


use Illuminate\Routing\Controller as IlluminateController;


class AdminController extends IlluminateController
{
	
	/**
	 * @var Api
	 */
	protected $api;
	
	
	public function __construct ( Api $api )
	{
		$this->api = $api;
	}
	
	
	public function manifest ()
	{
		return view ( 'finnegan-api::manifest', [
			'title'             => 'API Manifest',
			'icon'              => 'plug',
			'models'            => $this->api->getModels (),
			'modelNameCallback' => function ( $class ) {
				$reflection = new \ReflectionClass( $class );
				return strtolower ( str_plural ( $reflection->getShortName () ) );
			},
			'endpoints'         => $this->api->getEndpoints (),
		] );
	}
	
}