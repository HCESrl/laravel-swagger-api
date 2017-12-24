<?php

namespace Finnegan\Api\Endpoints;


use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


/**
 * @property array resources
 * @method AggregateEndpoint resources( array $resources )
 */
class AggregateEndpoint extends BaseEndpoint
{
	
	
	/**
	 * @param Registrar $router
	 * @return EndpointInterface
	 */
	public function register ( Registrar $router )
	{
		$this->action ( function () {
			return $this->aggregate ();
		} );
		
		return parent::register ( $router );
	}
	
	
	protected function aggregate ()
	{
		$data = Collection::make ();
		foreach ( $this->resources as $name => $resource )
		{
			if ( is_string ( $name ) and is_callable ( $resource ) )
			{
				$data[ $name ] = Collection::make ( app ()->call ( $resource ) );
			} elseif ( class_exists ( $resource ) )
			{
				$model = new $resource;
				$data [ Str::plural ( $model->name () ) ] = call_user_func ( [ $model, 'all' ] );
			}
		}
		return Resource::collection ( $data );
	}
	
	
}