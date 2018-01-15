<?php

namespace Finnegan\Api\Http\Controllers;


use Finnegan\Api\ApiServer;
use Illuminate\Routing\Controller as IlluminateController;


class SwaggerController extends IlluminateController
{
	
	public function index ( ApiServer $api )
	{
		if ( ! config ( 'finnegan-api.swagger_json_path' ) )
		{
			abort ( 404 );
		}
		return $api;
	}
	
}