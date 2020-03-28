<?php

namespace LaravelApi;


use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;


class ServiceProvider extends IlluminateServiceProvider
{


	public function register ()
	{
		$this->app->singleton ( Api::class );

		$this->registerModelEndpointRegistry ();

		if ( $this->app->runningInConsole () )
		{
			$this->commands (
				Console\ApiCacheCommand::class,
				Console\ApiClearCommand::class
			);
		}
	}


    /**
     * @return void
     */
	protected function registerModelEndpointRegistry ()
	{
		$this->app->singleton ( Endpoints\ModelsEndpointRegistry::class, function () {
			$registry = new Endpoints\ModelsEndpointRegistry( $this->app[ Api::class ] );

			$this->app[ 'router' ]->bind ( 'api_model', function ( $name ) use ( $registry ) {
				return $registry->resolve ( $name );
			} );

			return $registry;
		} );
	}


    /**
     * @return void
     */
	public function boot ()
	{
        $this->mergeConfigFrom ( __DIR__ . '/../resources/config.php', 'api' );

		$this->initRoutes ( $this->app[ 'router' ] );

		$resourcesPath = __DIR__ . '/../resources';

		$this->loadViewsFrom ( "$resourcesPath/views", 'api' );

		$this->publishes ( [ "$resourcesPath/config.php" => config_path ( 'api.php' ) ], 'config' );

        $swaggerPath = __DIR__ . '/../resources/dist/';
		$this->publishes ( [ $swaggerPath => public_path ( 'vendor/laravel-swagger-api' ) ], 'public' );
	}


    /**
     * @param \Illuminate\Routing\RouteRegistrar|\Illuminate\Contracts\Routing\Registrar $router
     */
	protected function initRoutes ( $router )
	{
		$router->prefix ( config ( 'api.prefix' ) )
			   ->middleware ( 'api' )
			   ->namespace ( 'LaravelApi\\Http\\Controllers' )
			   ->group ( function ( $router ) {

				   if ( $jsonPath = config ( 'api.swagger_json_path' ) )
				   {
					   $router->get ( $jsonPath, 'DocsController@json' )
							  ->name ( 'api.swagger' );

					   if ( $uiPath = config ( 'api.swagger_ui_path' ) )
					   {
						   $router->get ( $uiPath, 'DocsController@index' )
								  ->name ( 'api.docs' );
					   }
				   }

			   } );
	}


	public function provides ()
	{
		return [ Api::class, Endpoints\ModelsEndpointRegistry::class ];
	}

}


