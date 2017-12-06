<?php

namespace Finnegan\Api;


use Finnegan\Routing\Registrars\AbstractRegistrar;
use Illuminate\Contracts\Routing\Registrar;
use Spatie\Permission\Middlewares\PermissionMiddleware;


class RoutesRegistrar extends AbstractRegistrar
{
	
	protected $controller = AdminController::class;
	
	
	public function register ()
	{
		$this->secureGroup ( function ( Registrar $router ) {
			$router->group (
				[ 'middleware' => PermissionMiddleware::class . ':developer_tools' ],
				function ( Registrar $router ) {
					
					$router->get ( 'api-manifest', "{$this->controller}@manifest" )
						   ->name ( 'api-manifest' );
					
				} );
		} );
	}
}