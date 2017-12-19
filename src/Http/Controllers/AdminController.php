<?php

namespace Finnegan\Api\Http\Controllers;


use Finnegan\Api\ApiServer;
use Illuminate\Routing\Controller as IlluminateController;


class AdminController extends IlluminateController
{
	
	/**
	 * @var ApiServer
	 */
	protected $api;
	
	
	public function __construct ( ApiServer $api )
	{
		$this->api = $api;
	}
	
	
	public function manifest ()
	{
		return view ( 'finnegan-api::manifest', [
			'title'             => 'API Manifest',
			'icon'              => 'plug',
			'endpoints'         => $this->api->getEndpoints (),
			'methodMap'         => [
				'GET'    => 'success',
				'HEAD'   => 'secondary',
				'POST'   => 'primary',
				'PUT'    => 'warning',
				'PATCH'  => 'secondary',
				'DELETE' => 'alert'
			],
			'modelNameCallback' => function ( $class ) {
				$reflection = new \ReflectionClass( $class );
				return strtolower ( str_plural ( $reflection->getShortName () ) );
			},
		] );
	}
	
	
	public function manifestExport ()
	{
		return response ()->view ( 'finnegan-api::raml', [ 'api' => $this->api ] )
						  ->header ( 'Content-Type', 'application/raml+yaml' );
	}
	
}