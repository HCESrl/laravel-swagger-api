<?php

namespace Finnegan\Api;


use Finnegan\Finnegan;
use Finnegan\Routing\UrlGenerator;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as IlluminateController;


/**
 * @link https://github.com/anlutro/laravel-settings
 */
class Controller extends IlluminateController
{
	
	use ValidatesRequests;
	
	/**
	 * @var UrlGenerator
	 */
	protected $urls;
	
	
	public function __construct ( Finnegan $app, Gate $gate )
	{
		$this->urls = $app->make ( UrlGenerator::class );
		
		view ()->share ( 'gate', $gate );
	}
	
	
}