<?php

namespace Finnegan\Api;


use Finnegan\Api\Http\Controllers\AdminController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;


class ApiServiceProvider extends ServiceProvider
{
	
	protected $defer = true;
	
	
	public function register ()
	{
		$this->mergeConfigFrom ( __DIR__ . '/../resources/config.php', 'finnegan-api' );
		
		$this->app->singleton ( ApiServer::class );
		
		if ( ApiFinneganServiceProvider::isFinneganInstalled () )
		{
			$this->app->register ( ApiFinneganServiceProvider::class );
		}
	}
	
	
	public function boot ( Registrar $router )
	{
		$this->initRoutes ( $router );
		
		$this->loadViewsFrom ( __DIR__ . '/../resources/views', 'finnegan-api' );
		
		$this->publishes ( [ __DIR__ . '/../resources/config.php' => config_path ( 'finnegan-api.php' ) ], 'config' );
		
		$swaggerPath = base_path ( 'vendor/swagger-api/swagger-ui/dist/' );
		$this->publishes ( [ $swaggerPath => public_path ( 'vendor/swagger-ui' ) ], 'public' );
	}
	
	
	protected function initRoutes ( Registrar $router )
	{
		$router->prefix ( config ( 'finnegan-api.prefix' ) )
			   ->middleware ( 'api' )
			   ->namespace ( 'Finnegan\\Api\\Http\\Controllers' )
			   ->group ( function ( Registrar $router ) {
			
				   $router->get ( 'docs/swagger.json', 'SwaggerController@index' )
						  ->name ( 'finnegan-api.swagger' );
			
				   $router->get ( 'docs', 'AdminController@docs' )
						  ->name ( 'finnegan-api.docs' );
			
			   } );
	}
	
	
	public function provides ()
	{
		return [ ApiServer::class ];
	}
	
}


