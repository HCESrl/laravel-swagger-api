<?php

namespace Finnegan\Api\Endpoints;


use Calcinai\Strut\Definitions\PathItem;
use Illuminate\Routing\Route;


class Endpoint extends PathItem
{
	
	
	/**
	 * @param string $method
	 * @param Route  $route
	 * @return Operation
	 */
	public function getOperation ( $method, Route $route )
	{
		return $this->setMethod ( $method )
					->setRoute ( $route );
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