<?php

namespace Finnegan\Api;


use Illuminate\Contracts\Routing\Registrar;


class Api
{
	
	/**
	 * @var Registrar
	 */
	protected $router;
	
	/**
	 * @var array
	 */
	protected $models = [];
	
	/**
	 * @var array|ApiEndpoint[]
	 */
	protected $endpoints = [];
	
	
	/**
	 * @param Registrar $router
	 */
	public function __construct ( Registrar $router )
	{
		$this->router = $router;
	}
	
	
	/**
	 * @param string|array $models
	 * @return Api
	 */
	public function models ( $models )
	{
		$models = is_array ( $models ) ? $models : func_get_args ();
		
		$this->models = array_unique ( array_merge ( $this->models, $models ) );
		
		return $this;
	}
	
	
	/**
	 * @param string $model
	 * @return bool
	 */
	public function isWhitelisted ( $model )
	{
		return in_array ( $model, $this->models );
	}
	
	
	/**
	 * @return array
	 */
	public function getModels ()
	{
		return $this->models;
	}
	
	
	/**
	 * @param string          $uri
	 * @param string|callable $action
	 * @param string          $methods
	 * @return ApiEndpoint
	 */
	public function endpoint ( $uri, $action, $methods = 'get' )
	{
		$endpoint = new ApiEndpoint( compact ( 'uri', 'action', 'methods' ) );
		
		$this->endpoints[] = $endpoint;
		
		$attributes = [
			'prefix'     => 'api',
			'middleware' => 'api',
		];
		
		$this->router->group ( $attributes, function ( Registrar $router ) use ( $endpoint ) {
			$router->match ( $endpoint->methods, $endpoint->uri, $endpoint->action );
		} );
		
		return $endpoint;
	}
	
	
	/**
	 * @return array|ApiEndpoint[]
	 */
	public function getEndpoints ()
	{
		return $this->endpoints;
	}
	
}