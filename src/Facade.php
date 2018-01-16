<?php

namespace LaravelApi;


use Illuminate\Support\Facades\Facade as IlluminateFacade;


/**
 * @method static \Calcinai\Strut\Definitions\Tag tag ( string $name, string $description = null )
 *
 * @see \LaravelApi\Api
 */
class Facade extends IlluminateFacade
{
	
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor ()
	{
		return Api::class;
	}
}
