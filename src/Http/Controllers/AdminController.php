<?php

namespace Finnegan\Api\Http\Controllers;


use Finnegan\Api\ApiServer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as IlluminateController;


class AdminController extends IlluminateController
{
	
	
	public function docs ( Request $request, ApiServer $api )
	{
		if ( ! config ( 'api.swagger_ui_path' ) )
		{
			abort ( 404 );
		}
		return view ( 'api::swagger-ui', [
			'api'       => $api,
			'secure'    => $request->secure (),
			'urlToDocs' => route ( 'api.swagger' ),
		] );
	}
	
}