<?php

namespace Finnegan\Api\Endpoints;


use Finnegan\Api\Http\Controllers\ModelsEndpointController;
use Illuminate\Contracts\Routing\Registrar;


/**
 * @property array models
 * @method ModelsEndpoint models( array $models )
 */
class ModelsEndpoint extends AbstractEndpoint
{
	
	
	/**
	 * @param Registrar $router
	 * @return ModelsEndpoint
	 */
	public function register ( Registrar $router )
	{
		$attributes = [
			'prefix'     => 'api',
			'middleware' => 'api',
			'as'         => 'api.',
		];
		
		$router->group ( $attributes, function ( Registrar $router ) {
			
			$router->resource (
				'models/{api_model}',
				ModelsEndpointController::class,
				[ 'only' => [ 'index', 'show' ] ]
			);
			
		} );
		
		return $this;
	}
	
	
	/**
	 * @param array $models
	 * @return EndpointInterface
	 */
	public function merge ( array $models )
	{
		return $this->models ( array_unique ( array_merge ( $this->get ( 'models', [] ), $models ) ) );
	}
	
	
	/**
	 * @param string $model
	 * @return bool
	 */
	public function isWhitelisted ( $model )
	{
		return in_array ( $model, $this->get ( 'models', [] ) );
	}
	
	
	/**
	 * @return string
	 */
	public function view ()
	{
		return 'endpoint-models';
	}
	
	
}