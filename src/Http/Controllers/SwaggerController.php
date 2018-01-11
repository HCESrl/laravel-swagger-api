<?php

namespace Finnegan\Api\Http\Controllers;


use Finnegan\Api\ApiServer;
use Illuminate\Routing\Controller as IlluminateController;


class SwaggerController extends IlluminateController
{
	
	public function index ( ApiServer $api )
	{
		return $api;
	}
	
}