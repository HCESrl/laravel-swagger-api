<?php

namespace LaravelApi\Http\Controllers;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as IlluminateController;
use LaravelApi\Api;


class DocsController extends IlluminateController
{

	/**
	 * @var Api
	 */
	protected $api;


	public function __construct ( Api $api )
	{
		$this->api = $api;
	}


	public function index ( Request $request )
	{
		if ( ! config ( 'api.swagger_ui_path' ) )
		{
			abort ( 404 );
		}
		return view ( 'api::swagger-ui', [
            'api'          => $this->api,
            //'secure'     => $request->secure (),
            'urlToDocs'    => route('api.swagger'),
            'docExpansion' => config('api.doc_expansion', 'list'),
		] );
	}


	public function json ( Filesystem $files )
	{
		if ( ! config ( 'api.swagger_json_path' ) )
		{
			abort ( 404 );
		}
		if ( $files->exists ( $this->api->getCachedApiPath () ) )
		{
			return new JsonResponse ( json_decode ( $files->get ( $this->api->getCachedApiPath () ) ) );
		}
		return $this->api;
	}

}
