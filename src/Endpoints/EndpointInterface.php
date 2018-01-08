<?php

namespace Finnegan\Api\Endpoints;


use Illuminate\Contracts\Routing\Registrar;


/**
 * @property string $summary
 * @property string $description
 * @property array  $parameters
 * @property array  $examples
 * @method EndpointInterface summary( string $summary )
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
	 * @return \Calcinai\Strut\Definitions\PathItem
	 */
	public function toSwaggerPath ();
	
}