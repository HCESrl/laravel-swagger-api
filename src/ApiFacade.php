<?php

namespace Finnegan\Api;


use Illuminate\Support\Facades\Facade;


/**
 * @method static \Calcinai\Strut\Definitions\Tag tag ( string $name, string $description = null )
 *
 * @see \Finnegan\Api\ApiServer
 */
class ApiFacade extends Facade
{
	
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor ()
	{
		return ApiServer::class;
	}
}
