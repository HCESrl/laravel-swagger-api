<?php

namespace LaravelApi;


use Calcinai\Strut\Definitions\Definitions;
use Calcinai\Strut\Definitions\Info;
use Calcinai\Strut\Definitions\Paths;
use Calcinai\Strut\Definitions\Tag;
use Calcinai\Strut\Swagger;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Http\Request;
use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Support\Traits\Macroable;
use LaravelApi\Endpoints\Parameters\PathParameter;
use LaravelApi\Endpoints\ResourceEndpoint;
use LaravelApi\Http\Controllers\AggregateController;


/**
 * @method Endpoints\Operation get( string $uri, \Closure | array | string $action )
 * @method Endpoints\Operation post( string $uri, \Closure | array | string $action )
 * @method Endpoints\Operation put( string $uri, \Closure | array | string $action )
 * @method Endpoints\Operation delete( string $uri, \Closure | array | string $action )
 * @method Endpoints\Operation patch( string $uri, \Closure | array | string $action )
 * @method Endpoints\Operation options( string $uri, \Closure | array | string $action )
 * @method group ( array $attributes, \Closure | string $routes )
 */
class Api implements \JsonSerializable
{
	
	use Macroable
	{
		__call as macroCall;
	}
	
	/**
	 * @var Container
	 */
	protected $app;
	
	/**
	 * @var Registrar
	 */
	protected $router;
	
	/**
	 * @var Swagger
	 */
	protected $swagger;
	
	/**
	 * @var array|PathParameter[]
	 */
	protected $parameters = [];
	
	/**
	 * @var array
	 */
	protected $passthru = [ 'group' ];
	
	/**
	 * @var array
	 */
	protected $passthruVerbs = [ 'get', 'post', 'put', 'delete', 'patch', 'options' ];
	
	
	/**
	 * @param Container $app
	 * @param Registrar $router
	 * @param Request   $request
	 */
	public function __construct ( Container $app, Registrar $router, Request $request )
	{
		$this->app = $app;
		
		$this->router = $router;
		
		$this->swagger = Swagger::create ()
								->setInfo ( $this->buildInfo () )
								->setHost ( $request->getHost () )
								->setBasePath ( '/' . config ( 'api.prefix', 'api' ) )
								->addScheme ( config ( 'api.scheme', $request->getScheme () ) )
								->setConsumes ( [ 'application/json' ] )
								->setProduces ( [ 'application/json' ] )
								->setDefinitions ( Definitions::create () )
								->setPaths ( Paths::create () );
	}
	
	
	/**
	 * @return Info
	 */
	protected function buildInfo ()
	{
		$title = config ( 'api.title', config ( 'app.name' ) . ' API' );
		
		return Info::create ()
				   ->setTitle ( $title )
				   ->setDescription ( config ( 'api.description' ) )
				   ->setVersion ( config ( 'api.version', '1.0.0' ) );
	}
	
	
	/**
	 * @return string
	 */
	public function title ()
	{
		return $this->swagger->getInfo ()->getTitle ();
	}
	
	
	/**
	 * @return mixed
	 */
	public function jsonSerialize ()
	{
		return $this->swagger->jsonSerialize ();
	}
	
	
	/**
	 * @param string          $name
	 * @param string          $description
	 * @param \Closure|string $callback
	 * @return Tag
	 */
	public function tag ( $name, $description = null, $callback = null )
	{
		$tag = new Tag( compact ( 'name', 'description' ) );
		
		$this->swagger->addTag ( $tag );
		
		if ( ! is_null ( $callback ) )
		{
			$this->router->group ( [ 'tags' => $name ], $callback );
		}
		
		return $tag;
	}
	
	
	/**
	 * @param array $tags
	 * @return Api
	 */
	public function tags ( array $tags )
	{
		foreach ( $tags as $name => $description )
		{
			$this->tag ( $name, $description );
		}
		return $this;
	}
	
	
	/**
	 * @param string $name
	 * @return Definition
	 * @throws \Exception
	 */
	public function definition ( $name )
	{
		$definition = Definition::create ()->setName ( $name );
		
		$this->swagger->getDefinitions ()->set ( $name, $definition );
		
		return $definition;
	}
	
	
	/**
	 * @param string          $version
	 * @param \Closure|string $routes
	 * @return void
	 */
	public function version ( $version, $routes )
	{
		$this->router->group ( [ 'prefix' => $version, 'tags' => $version ], $routes );
	}
	
	
	/**
	 * @param string $name
	 * @param string $controller
	 * @param array  $options
	 * @return ResourceEndpoint
	 */
	public function resource ( $name, $controller, array $options = [] )
	{
		if ( $this->app && $this->app->bound ( ResourceRegistrar::class ) )
		{
			$registrar = $this->app->make ( ResourceRegistrar::class );
		} else
		{
			$registrar = new ResourceRegistrar( $this->router );
		}
		
		$options = array_merge ( [ 'only' => [ 'index', 'show', 'store', 'update', 'destroy' ], ], $options );
		
		return ( new ResourceEndpoint( $registrar, $name, $controller, $options ) )
			->setApi ( $this );
	}
	
	
	/**
	 * @param array $resources
	 */
	public function resources ( array $resources )
	{
		foreach ( $resources as $name => $controller )
		{
			$this->resource ( $name, $controller );
		}
	}
	
	
	/**
	 * @param string $name
	 * @return PathParameter
	 */
	public function routeParameter ( $name )
	{
		$parameter = new PathParameter ( compact ( 'name' ) );
		
		$this->parameters[ $name ] = $parameter;
		
		return $parameter;
	}
	
	
	/**
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed
	 */
	public function __call ( $name, $arguments )
	{
		if ( in_array ( $name, $this->passthru ) )
		{
			return call_user_func_array ( [ $this->router, $name ], $arguments );
		}
		
		if ( in_array ( $name, $this->passthruVerbs ) )
		{
			$route = call_user_func_array ( [ $this->router, $name ], $arguments );
			
			$operation = $this->getEndpointByUri ( $route->uri () )
							  ->getOperation ( $name, $route, $this->parameters );
			
			return $operation;
		}
		
		return $this->macroCall ( $name, $arguments );
	}
	
	
	/**
	 * @param string $uri
	 * @return Endpoints\Endpoint
	 */
	public function getEndpointByUri ( $uri )
	{
		$uri = $this->cleanUpRouteUri ( $uri );
		
		$paths = $this->swagger->getPaths ();
		
		if ( ! $paths->has ( $uri ) )
		{
			$paths->set ( $uri, new Endpoints\Endpoint );
		}
		
		return $paths->get ( $uri );
	}
	
	
	/**
	 * @param string $uri
	 * @return string
	 */
	protected function cleanUpRouteUri ( $uri )
	{
		$basePath = trim ( $this->swagger->getBasePath (), '/' );
		$uri = preg_replace ( "/^{$basePath}/", '', $uri );
		return '/' . trim ( $uri, '/' );
	}
	
	
	/**
	 * @param string $uri
	 * @param array  $resources
	 * @return Endpoints\Operation
	 */
	public function aggregate ( $uri, array $resources )
	{
		$controller = AggregateController::class;
		
		$route = $this->router->get ( $uri, "\\{$controller}@index" )
							  ->defaults ( 'resources', $resources );
		
		$operation = $this->getEndpointByUri ( $route->uri () )
						  ->getOperation ( 'get', $route );
		
		return $operation;
	}
	
	
	/**
	 * @param array|string $models
	 * @return Endpoints\ModelsEndpointRegistry
	 */
	public function models ( $models )
	{
		$models = is_array ( $models ) ? $models : func_get_args ();
		
		$registry = app ( Endpoints\ModelsEndpointRegistry::class );
		
		$registry->add ( $models );
		
		return $registry;
	}
	
	
	/**
	 * Get the path to the API cache file.
	 * @return string
	 */
	public function getCachedApiPath ()
	{
		return $this->app->bootstrapPath () . '/cache/api.json';
	}
	
}