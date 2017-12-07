<?php

namespace Finnegan\Api;


use Finnegan\Finnegan;
use Finnegan\Layout\MenuRegister;
use Finnegan\Layout\Icons\IconsManager;
use Finnegan\Layout\ViewComposer;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\ServiceProvider;


class ApiServiceProvider extends ServiceProvider
{
	
	protected $defer = true;
	
	
	public function register ()
	{
		$this->app->singleton ( Api::class );
		
		$this->app->make ( Finnegan::class )->loadRegistrar ( RoutesRegistrar::class );
	}
	
	
	public function boot ( Registrar $router, MenuRegister $menu, IconsManager $icons )
	{
		$this->registerRoutes ( $router );
		
		$this->loadViewsFrom ( __DIR__ . '/../resources/views', 'finnegan-api' );
		
		$this->app[ 'view' ]->composer ( 'finnegan-api::*', ViewComposer::class );
		
		$menu->tools->route ( 'api-manifest', $icons->icon ( 'plug' ) . ' API Manifest' );
	}
	
	
	protected function registerRoutes ( Registrar $router )
	{
		//@todo skippa authorize, da ricontrollare
		$router->bind ( 'api_model', function ( $name ) {
			return $this->app[ 'models.resolver' ]->resolve ( $name, false );
		} );
		
		$router->group ( [
							 'prefix'     => 'api',
							 'middleware' => 'api',
							 'as'         => 'api.',
						 ], function ( Registrar $router ) {
			
			$router->resource ( 'models/{api_model}', Controller::class, [ 'only' => [ 'index', 'show' ] ] );
			
		} );
	}
	
	
	public function provides ()
	{
		return [ Api::class ];
	}
	
}


