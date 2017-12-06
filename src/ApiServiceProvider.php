<?php

namespace Finnegan\Api;


use Finnegan\Finnegan;
use Finnegan\Layout\MenuRegister;
use Finnegan\Layout\Icons\IconsManager;
use Illuminate\Support\ServiceProvider;


class ApiServiceProvider extends ServiceProvider
{
	
	
	public function register ()
	{
		$this->app->make ( Finnegan::class )->loadRegistrar ( RoutesRegistrar::class );
		
		$this->app->singleton ( Api::class );
	}
	
	
	public function boot ( MenuRegister $menu, IconsManager $icons )
	{
		//$menu->routeIfCan ( 'primary', 'configuration', 'manage_configuration', 'edit-settings', $icons->icon ( 'cog' ) . ' Settings' );
	}
	
	
	public function provides ()
	{
		return [ Api::class ];
	}
	
}


