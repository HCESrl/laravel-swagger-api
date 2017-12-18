<?php

namespace Finnegan\Api\Http;


use Finnegan\Routing\Registrars\AbstractRegistrar;
use Illuminate\Contracts\Routing\Registrar;
use Spatie\Permission\Middlewares\PermissionMiddleware;


class RoutesRegistrar extends AbstractRegistrar
{
	
	protected $controller = Controllers\AdminController::class;
	
	
	public function register ()
	{
		$this->secureGroup ( function ( Registrar $router ) {
			$router->group (
				[ 'middleware' => PermissionMiddleware::class . ':developer_tools' ],
				function ( Registrar $router ) {
					
					$router->get ( 'tools/api-manifest', "{$this->controller}@manifest" )
						   ->name ( 'api-manifest' );
					
				} );
		} );
	}
}