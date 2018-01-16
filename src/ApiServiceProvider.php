<?php

namespace Finnegan\Api;


use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;


class ApiServiceProvider extends ServiceProvider
{
	
	protected $defer = true;
	
	
	public function register ()
	{
		$this->mergeConfigFrom ( __DIR__ . '/../resources/config.php', 'api' );
		
		$this->app->singleton ( ApiServer::class );
		
		
		if ( $this->app->runningInConsole () )
		{
			$this->commands (
				Console\ApiCacheCommand::class,
				Console\ApiClearCommand::class
			);
		}
	}
	
	
	public function boot ( Registrar $router )
	{
		$this->initRoutes ( $router );
		
		$resourcesPath = __DIR__ . '/../resources';
		
		$this->loadViewsFrom ( "$resourcesPath/views", 'api' );
		
		$this->publishes ( [ "$resourcesPath/config.php" => config_path ( 'api.php' ) ], 'config' );
		
		$swaggerPath = base_path ( 'vendor/swagger-api/swagger-ui/dist/' );
		$this->publishes ( [ $swaggerPath => public_path ( 'vendor/swagger-ui' ) ], 'public' );
	}
	
	
	protected function initRoutes ( Registrar $router )
	{
		$router->prefix ( config ( 'api.prefix' ) )
			   ->middleware ( 'api' )
			   ->namespace ( 'Finnegan\\Api\\Http\\Controllers' )
			   ->group ( function ( Registrar $router ) {
			
				   if ( $jsonPath = config ( 'api.swagger_json_path' ) )
				   {
					   $router->get ( $jsonPath, 'SwaggerController@index' )
							  ->name ( 'api.swagger' );
				
					   if ( $uiPath = config ( 'api.swagger_ui_path' ) )
					   {
						   $router->get ( $uiPath, 'AdminController@docs' )
								  ->name ( 'api.docs' );
					   }
				   }
			
			   } );
	}
	
	
	public function provides ()
	{
		return [ ApiServer::class ];
	}
	
}


