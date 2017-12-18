<?php

namespace Finnegan\Api\Endpoints;


use Illuminate\Contracts\Routing\Registrar;


/**
 * @property string $description
 * @property array  $parameters
 * @property array  $examples
 * @method EndpointInterface description( string $description )
 * @method EndpointInterface parameters( array $parameters )
 * @method EndpointInterface examples( array $examples )
 */
interface EndpointInterface
{
	
	/**
	 * @param Registrar $router
	 * @return EndpointInterface
	 */
	public function register ( Registrar $router );
	
	
	/**
	 * @return string
	 */
	public function view ();
	
}