<?php

namespace Finnegan\Api\Endpoints;


use Calcinai\Strut\Definitions\Operation;
use Calcinai\Strut\Definitions\PathItem;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Str;


/**
 * @property string          $uri
 * @property string|callable $action
 * @property string|array    $methods
 * @method BaseEndpoint uri( string $uri )
 * @method BaseEndpoint action( string | callable $action )
 * @method BaseEndpoint methods( string | array $methods )
 */
class BaseEndpoint extends AbstractEndpoint
{
	
	/**
	 * @param Registrar $router
	 * @return EndpointInterface
	 */
	public function register ( Registrar $router )
	{
		$attributes = [
			'prefix'     => 'api',
			'middleware' => 'api',
		];
		
		$router->group ( $attributes, function ( Registrar $router ) {
			$router->match ( $this->methods, $this->uri, $this->action );
		} );
		
		return $this;
	}
	
	
	public function toSwaggerPath ()
	{
		$path = PathItem::create ();
		
		foreach ( (array) $this->methods as $method )
		{
			call_user_func_array ( [ $path, 'set' . Str::studly ( $method ) ], [
				Operation::create ()
						 ->setSummary ( $this->summary )
						 ->setDescription ( $this->description )
						 ->setOperationId ( Str::camel ( $this->uri ) )
			] );
		}
		
		return $path;
	}
	
	
}