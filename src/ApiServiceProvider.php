<?php

namespace Finnegan\Api;


use Finnegan\Api\Endpoints\ModelsEndpoint;
use Finnegan\Api\Http\Controllers\AdminController;
use Finnegan\Layout\MenuRegister;
use Finnegan\Layout\Icons\IconsManager;
use Finnegan\Layout\ViewComposer;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;


class ApiServiceProvider extends ServiceProvider
{
	
	protected $defer = true;
	
	
	public function register ()
	{
		$this->app->singleton ( ApiServer::class );
		
		$this->app->singleton ( ModelsEndpoint::class, function () {
			$endpoint = new ModelsEndpoint;
			
			$endpoint->register ( $this->app[ 'router' ] );
			
			return $endpoint;
		} );
		
		Route::macro ( 'endpoint', function ( $callback ) {
			dd($this);
			$endpoint = app ( ApiServer::class )->endpoint ();
			
			$callback( $endpoint );
			
			return $endpoint;
		} );
	}
	
	
	public function boot ( Registrar $router, MenuRegister $menu, IconsManager $icons )
	{
		$this->initRoutes ( $router );
		
		$this->publishes ( [ __DIR__ . '/../resources/config.php' => config_path ( 'finnegan-api.php' ) ], 'config' );
		
		$this->mergeConfigFrom ( __DIR__ . '/../resources/config.php', 'finnegan-api' );
		
		if ( $this->isFinneganInstalled () )
		{
			$this->loadViewsFrom ( __DIR__ . '/../resources/views', 'finnegan-api' );
			
			$this->app[ 'view' ]->composer ( 'finnegan-api::*', ViewComposer::class );
			
			$menu->tools->url ( 'api/docs', $icons->icon ( 'plug' ) . ' API Manifest' );
		}
	}
	
	
	protected function initRoutes ( Registrar $router )
	{
		$router->group (
			[
				'prefix'     => 'api/docs',
				'middleware' => 'api',
				'namespace'  => 'Finnegan\\Api\\Http\\Controllers',
			], function ( Registrar $router ) {
			
			$router->get ( 'swagger.json', 'AdminController@swaggerJson' )
				   ->name ( 'finnegan-api.swagger' );
			
			$router->get ( '/', 'AdminController@docs' );
			
		} );
		
		//@todo skippa authorize, da ricontrollare
		$router->bind ( 'api_model', function ( $name ) {
			return $this->app[ 'models.resolver' ]->resolve ( $name, false );
		} );
	}
	
	
	private function isFinneganInstalled ()
	{
		return class_exists ( 'Finnegan\\Finnegan' );
	}
	
	
	public function provides ()
	{
		return [ ApiServer::class ];
	}
	
}


