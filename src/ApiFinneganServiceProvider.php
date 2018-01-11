<?php

namespace Finnegan\Api;


use Finnegan\Api\Http\RoutesRegistrar;
use Finnegan\Finnegan;
use Finnegan\Layout\Icons\IconsManager;
use Finnegan\Layout\MenuRegister;
use Finnegan\Layout\ViewComposer;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\ServiceProvider;


class ApiFinneganServiceProvider extends ServiceProvider
{
	
	
	public static function isFinneganInstalled ()
	{
		return class_exists ( 'Finnegan\\Finnegan' );
	}
	
	
	public function register ()
	{
	}
	
	
	public function boot ( MenuRegister $menus, IconsManager $icons, Registrar $router )
	{
		$this->app->make ( Finnegan::class )
				  ->loadRegistrar ( RoutesRegistrar::class );
		
		//@todo skippa authorize, da ricontrollare
		$router->bind ( 'api_model', function ( $name ) {
			return $this->app[ 'models.resolver' ]->resolve ( $name, false );
		} );
		
		$this->app[ 'view' ]->composer ( 'finnegan-api::*', ViewComposer::class );
		
		$menus->tools->route ( 'api-docs', $icons->icon ( 'plug' ) . ' API Docs' );
	}
	
}