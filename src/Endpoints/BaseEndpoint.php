<?php

namespace Finnegan\Api\Endpoints;


use Illuminate\Contracts\Routing\Registrar;


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
	
	
	public function view ()
	{
		return 'endpoint';
	}
	
	
}