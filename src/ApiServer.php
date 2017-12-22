<?php

namespace Finnegan\Api;


use Finnegan\Api\Endpoints\EndpointInterface;
use Finnegan\Api\Endpoints\ModelsEndpoint;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Routing\Registrar;


class ApiServer
{
	
	/**
	 * @var Container
	 */
	protected $app;
	
	/**
	 * @var Registrar
	 */
	protected $router;
	
	
	/**
	 * @var array|EndpointInterface[]
	 */
	protected $endpoints = [];
	
	
	/**
	 * @param Container $app
	 * @param Registrar $router
	 */
	public function __construct ( Container $app, Registrar $router )
	{
		$this->app = $app;
		$this->router = $router;
	}
	
	
	/**
	 * @param EndpointInterface $endpoint
	 * @return EndpointInterface
	 */
	protected function registerEndpoint ( EndpointInterface $endpoint )
	{
		$this->endpoints[] = $endpoint->register ( $this->router );
		
		return $endpoint;
	}
	
	
	/**
	 * @return array|EndpointInterface[]
	 */
	public function getEndpoints ()
	{
		return $this->endpoints;
	}
	
	
	/**
	 * @param string          $uri
	 * @param string|callable $action
	 * @param string          $methods
	 * @return Endpoints\BaseEndpoint|EndpointInterface
	 */
	public function endpoint ( $uri, $action, $methods = 'get' )
	{
		return $this->registerEndpoint (
			new Endpoints\BaseEndpoint( compact ( 'uri', 'action', 'methods' ) )
		);
	}
	
	
	/**
	 * @param string $uri
	 * @param array  $resources
	 * @param string $methods
	 * @return Endpoints\AggregateEndpoint|EndpointInterface
	 */
	public function aggregate ( $uri, array $resources, $methods = 'get' )
	{
		return $this->registerEndpoint (
			new Endpoints\AggregateEndpoint( compact ( 'uri', 'methods', 'resources' ) )
		);
	}
	
	
	/**
	 * @param string|array $models
	 * @return ModelsEndpoint
	 */
	public function models ( $models )
	{
		$shouldRegister = ! $this->app->resolved ( Endpoints\ModelsEndpoint::class );
		
		$endpoint = $this->app->make ( Endpoints\ModelsEndpoint::class );
		
		$endpoint->merge ( is_array ( $models ) ? $models : func_get_args () );
		
		if ( $shouldRegister )
		{
			$this->endpoints[] = $endpoint;
		}
		
		return $endpoint;
	}
	
}