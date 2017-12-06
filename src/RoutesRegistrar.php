<?php

namespace Finnegan\Settings;


use Finnegan\Routing\Registrars\AbstractRegistrar;
use Illuminate\Contracts\Routing\Registrar;


class RoutesRegistrar extends AbstractRegistrar
{
	
	protected $controller = Controller::class;
	
	
	public function register ()
	{
		/*$this->secureGroup ( function ( Registrar $router ) {
			$router->group (
				[ 'middleware' => PermissionMiddleware::class . ':manage_configuration' ],
				function ( Registrar $router ) {
					
					$router->get ( 'settings', "{$this->controller}@edit" )
						   ->name ( 'edit-settings' );
					
					$router->match ( [ 'PUT', 'PATCH' ], 'settings', "{$this->controller}@store" )
						   ->name ( 'store-settings' );
				}
			);
		} );*/
	}
}