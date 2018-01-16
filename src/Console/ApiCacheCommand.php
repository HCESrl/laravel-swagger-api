<?php

namespace LaravelApi\Console;


use LaravelApi\Api;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\RouteCollection;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;


class ApiCacheCommand extends Command
{
	
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'api:cache';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create an API cache file for faster inspection';
	
	/**
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;
	
	/**
	 * @var Api
	 */
	protected $api;
	
	
	/**
	 * Create a new route command instance.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem $files
	 * @return void
	 */
	public function __construct ( Filesystem $files, Api $api )
	{
		parent::__construct ();
		
		$this->files = $files;
		
		$this->api = $api;
	}
	
	
	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle ()
	{
		$this->call ( 'api:clear' );
		
		if ( $routesAreCached = $this->laravel->routesAreCached () )
		{
			$this->callSilent ( 'route:clear' );
		}
		
		$routes = $this->getFreshApplicationRoutes ();
		if ( count ( $routes ) == 0 )
		{
			return $this->error ( "Your application doesn't have any routes." );
		}
		
		$this->files->put (
			$this->api->getCachedApiPath (), json_encode ( $this->api )
		);
		
		if ( $routesAreCached )
		{
			$this->callSilent ( 'route:cache' );
		}
		
		$this->info ( 'API cached successfully!' );
	}
	
	
	/**
	 * Boot a fresh copy of the application and get the routes.
	 *
	 * @return \Illuminate\Routing\RouteCollection
	 */
	protected function getFreshApplicationRoutes ()
	{
		return tap ( $this->getFreshApplication ()[ 'router' ]->getRoutes (), function ( $routes ) {
			$routes->refreshNameLookups ();
			$routes->refreshActionLookups ();
		} );
	}
	
	
	/**
	 * Get a fresh application instance.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	protected function getFreshApplication ()
	{
		return tap ( require $this->laravel->bootstrapPath () . '/app.php', function ( $app ) {
			$app->make ( ConsoleKernelContract::class )->bootstrap ();
		} );
	}
}
