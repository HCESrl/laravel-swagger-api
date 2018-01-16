<?php

namespace LaravelApi\Http\Controllers;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as IlluminateController;
use LaravelApi\ApiServer;


class SwaggerController extends IlluminateController
{
	
	public function index ( ApiServer $api, Filesystem $files )
	{
		if ( ! config ( 'api.swagger_json_path' ) )
		{
			abort ( 404 );
		}
		if ( $files->exists ( $api->getCachedApiPath () ) )
		{
			return new JsonResponse ( json_decode ( $files->get ( $api->getCachedApiPath () ) ) );
		}
		return $api;
	}
	
}