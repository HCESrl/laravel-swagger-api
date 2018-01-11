<?php

namespace Finnegan\Api\Endpoints;


use Calcinai\Strut\Definitions\PathItem;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;


/**
 * @method Route defaults( string $key, mixed $value )
 */
class Endpoint extends PathItem
{
	
	/**
	 * @var Route
	 */
	protected $route;
	
	
	/**
	 * @param string $name
	 * @param array  $arguments
	 * @return Endpoint
	 */
	public function __call ( $name, $arguments )
	{
		if ( method_exists ( $this->route, $name ) )
		{
			call_user_func_array ( [ $this->route, $name ], $arguments );
			return $this;
		}
	}
	
	
	/**
	 * @param string $method
	 * @param Route  $route
	 * @return Operation
	 */
	public function getOperation ( $method, Route $route )
	{
		$this->route = $route;
		
		$operation = $this->setMethod ( $method );
		
		$operation->initTags ( (array) $route->getAction ( 'tags' ) );
		
		if ( ! $route->getAction ( 'uses' ) instanceof \Closure )
		{
			$operation->initOperationId ( $route );
		}
		
		return $operation;
	}
	
	
	/**
	 * @param string $method
	 * @return Operation
	 */
	public function setMethod ( $method )
	{
		if ( ! $this->has ( strtolower ( $method ) ) )
		{
			$this->{'set' . ucfirst ( $method )}( new Operation );
		}
		
		return $this->{'get' . ucfirst ( $method )}();
	}
	
}