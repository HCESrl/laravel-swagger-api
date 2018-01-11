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
				[
					'prefix'     => 'tools',
					'middleware' => PermissionMiddleware::class . ':developer_tools',
				],
				function ( Registrar $router ) {
					
					$router->view ( 'api-docs', 'finnegan-api::embed' )
						   ->name ( 'api-docs' );
					
				} );
		} );
		
	}
	
}