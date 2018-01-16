<?php

namespace LaravelApi\Endpoints;


use Calcinai\Strut\Definitions\PathItem;
use Illuminate\Routing\Route;


class Endpoint extends PathItem
{
	
	
	/**
	 * @param string $method
	 * @param Route  $route
	 * @param array  $parameters
	 * @return Operation
	 */
	public function getOperation ( $method, Route $route, array $parameters = [] )
	{
		return $this->setMethod ( $method )
					->setRoute ( $route, $parameters );
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