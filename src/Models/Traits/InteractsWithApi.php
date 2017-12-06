<?php

namespace Finnegan\Api\Models\Traits;


use Illuminate\Http\Resources\Json\Resource;


trait InteractsWithApi
{
	
	
	/**
	 * @param mixed $resource
	 * @return \Illuminate\Http\Resources\Json\Resource
	 */
	public function __toApiResource ( $resource )
	{
		return new Resource( $resource );
	}
	
	
	/**
	 * @param mixed $resource
	 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 */
	public function __toApiResourceCollection ( $resource )
	{
		return Resource::collection ( $resource );
	}
	
}