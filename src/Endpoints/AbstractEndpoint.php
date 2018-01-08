<?php

namespace Finnegan\Api\Endpoints;


use Illuminate\Support\Fluent;


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
abstract class AbstractEndpoint extends Fluent implements EndpointInterface
{
	
}