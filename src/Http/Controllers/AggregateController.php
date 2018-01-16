<?php

namespace LaravelApi\Http\Controllers;


use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Routing\Controller as IlluminateController;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class AggregateController extends IlluminateController
{
	
	
	public function index ( array $resources )
	{
		$data = Collection::make ();
		foreach ( $resources as $name => $resource )
		{
			if ( is_string ( $name ) and is_callable ( $resource ) )
			{
				$data[ $name ] = Collection::make ( app ()->call ( $resource ) );
			} elseif ( is_string ( $resource ) and class_exists ( $resource ) )
			{
				$model = new $resource;
				
				$name = Str::snake ( ( new \ReflectionClass( $model ) )->getShortName () );
				
				$data [ Str::plural ( $name ) ] = call_user_func ( [ $model, 'all' ] );
			}
		}
		return Resource::collection ( $data );
	}
	
}