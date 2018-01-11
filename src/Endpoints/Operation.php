<?php

namespace Finnegan\Api\Endpoints;


use Calcinai\Strut\Definitions\FormDataParameterSubSchema;
use Calcinai\Strut\Definitions\Operation as StrutOperation;
use Calcinai\Strut\Definitions\PathParameterSubSchema;
use Calcinai\Strut\Definitions\QueryParameterSubSchema;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;


class Operation extends StrutOperation
{
	
	/**
	 * @param string   $name
	 * @param callable $callback
	 * @return Operation
	 */
	public function addQueryParameter ( $name, $callback = null )
	{
		return $this->registerParameter ( QueryParameterSubSchema::class, $name, $callback );
	}
	
	
	/**
	 * @param string   $name
	 * @param callable $callback
	 * @return Operation
	 */
	public function addPathParameter ( $name, $callback = null )
	{
		return $this->registerParameter ( PathParameterSubSchema::class, $name, $callback );
	}
	
	
	/**
	 * @param string   $name
	 * @param callable $callback
	 * @return Operation
	 */
	public function addFormDataParameter ( $name, $callback = null )
	{
		return $this->registerParameter ( FormDataParameterSubSchema::class, $name, $callback );
	}
	
	
	/**
	 * @param string   $parameter
	 * @param string   $name
	 * @param callable $callback
	 * @return Operation
	 */
	protected function registerParameter ( $parameter, $name, $callback = null )
	{
		$parameter = new $parameter( compact ( 'name' ) );
		
		if ( $callback instanceof \Closure )
		{
			$callback( $parameter );
		}
		
		return $this->addParameter ( $parameter );
	}
	
	
	/**
	 * @param array $tags
	 * @return Operation
	 */
	public function initTags ( array $tags )
	{
		foreach ( $tags as $tag )
		{
			$this->addTag ( $tag );
		}
		return $this;
	}
	
	
	/**
	 * @param Route $route
	 * @return Operation
	 */
	public function initOperationId ( Route $route )
	{
		//$method = strtolower ( array_first ( $route->methods () ) );
		
		$operationId = Str::camel ( $route->getActionMethod () );
		
		return $this->setOperationId ( $operationId );
	}
	
	
}