<?php

namespace Finnegan\Api;


use Finnegan\Api\Endpoints\ModelsEndpoint;
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
		$this->app->singleton ( ApiServer::class );
		
		$this->app->alias ( Api::class, ApiServer::class );
		
		$this->app->singleton ( ModelsEndpoint::class, function () {
			$endpoint = new ModelsEndpoint;
			
			$endpoint->register ( $this->app[ 'router' ] );
			
			return $endpoint;
		} );
		
		$this->app->make ( Finnegan::class )
				  ->loadRegistrar ( Http\RoutesRegistrar::class );
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
	}
	
	
	public function provides ()
	{
		return [ ApiServer::class, Api::class ];
	}
	
}


