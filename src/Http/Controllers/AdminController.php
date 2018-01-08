<?php

namespace Finnegan\Api\Http\Controllers;


use Calcinai\Strut\Definitions\Info;
use Calcinai\Strut\Definitions\License;
use Calcinai\Strut\Definitions\Operation;
use Calcinai\Strut\Definitions\PathItem;
use Calcinai\Strut\Definitions\Paths;
use Calcinai\Strut\Swagger;
use Finnegan\Api\ApiServer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as IlluminateController;


class AdminController extends IlluminateController
{
	
	/**
	 * @var ApiServer
	 */
	protected $api;
	
	
	public function __construct ( ApiServer $api )
	{
		$this->api = $api;
	}
	
	
	public function docs ( Request $request )
	{
		config ( [ 'l5-swagger.api.title' => $this->api->title () ] );
		
		return view ( 'l5-swagger::index', [
			'secure'           => $request->secure (),
			'urlToDocs'        => route ( 'finnegan-api.swagger' ),
			'operationsSorter' => config ( 'l5-swagger.operations_sort' ),
			'configUrl'        => config ( 'l5-swagger.additional_config_url' ),
			'validatorUrl'     => config ( 'l5-swagger.validator_url' ),
		] );
	}
	
	
	public function swaggerJson ( Request $request )
	{
		return $this->api->toSwagger ( $request );
	}
	
}