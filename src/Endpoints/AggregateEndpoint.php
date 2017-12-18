<?php

namespace Finnegan\Api\Endpoints;


use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


/**
 * @property array models
 * @method AggregateEndpoint models( array $models )
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
			$data = Collection::make ();
			foreach ( $this->models as $modelClass )
			{
				$model = new $modelClass;
				$data [ Str::plural ( $model->name () ) ] = call_user_func ( [ $model, 'all' ] );
			}
			return Resource::collection ( $data );
		} );
		
		return parent::register ( $router );
	}
	
	
}