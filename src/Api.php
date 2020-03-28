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

    use Auth\DefinesAuthorization;
    use Macroable
    {
        __call as macroCall;
    }

    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * @var \Illuminate\Contracts\Routing\Registrar|\Illuminate\Routing\Router
     */
    protected $router;

    /**
     * @var \Calcinai\Strut\Swagger
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
     * @param \Illuminate\Contracts\Container\Container $app
     * @param \Illuminate\Contracts\Routing\Registrar   $router
     * @param \Illuminate\Http\Request                  $request
     */
    public function __construct ( Container $app, Registrar $router, Request $request )
    {
        $this->app = $app;

        $this->router = $router;

        $this->swagger = Swagger::create ()
                                ->setInfo ( $this->buildInfo () )
                                ->setHost ( $request->getHttpHost () )
                                ->setBasePath ( '/' . config ( 'api.prefix', 'api' ) )
                                ->addScheme ( config ( 'api.scheme', $request->getScheme () ) )
                                ->setConsumes ( [ 'application/json' ] )
                                ->setProduces ( [ 'application/json' ] )
                                ->setDefinitions ( Definitions::create () )
                                ->setPaths ( Paths::create () );
    }


    /**
     * @return \Calcinai\Strut\Swagger
     */
    public function swagger ()
    {
        return $this->swagger;
    }


    /**
     * @return \Calcinai\Strut\Definitions\Info
     */
    protected function buildInfo ()
    {
        return Info::create ()
                   ->setTitle ( config ( 'api.title', config ( 'app.name' ) . ' API' ) )
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
     *
     * @return \Calcinai\Strut\Definitions\Tag
     */
    public function tag ( $name, $description = null, $callback = null )
    {
        $this->swagger->addTag ( $tag = Tag::create ( compact ( 'name', 'description' ) ) );

        if ( ! is_null ( $callback ) )
        {
            $this->router->group ( [ 'tags' => $name ], $callback );
        }

        return $tag;
    }


    /**
     * @param array $tags
     */
    public function tags ( array $tags )
    {
        foreach ( $tags as $name => $description )
        {
            $this->tag ( $name, $description );
        }
    }


    /**
     * @param string $name
     *
     * @return \LaravelApi\Definition
     * @throws \Exception
     */
    public function definition ( $name )
    {
        $definition = Definition::create ()->setName ($name );

        $this->swagger->getDefinitions ()->set ( $name, $definition );

        return $definition;
    }


    /**
     * @param string          $version
     * @param \Closure|string $routes
     */
    public function version ( $version, $routes )
    {
        $this->router->group ( [ 'prefix' => $version, 'tags' => $version ], $routes );
    }


    /**
     * @param string $name
     * @param string $controller
     * @param array  $options
     *
     * @return \LaravelApi\Endpoints\ResourceEndpoint
     */
    public function resource ( $name, $controller, array $options = [] )
    {
        $registrar = $this->app->make ( ResourceRegistrar::class );

        $options = array_merge ( [ 'only' => [ 'index', 'show', 'store', 'update', 'destroy' ], ], $options );

        return ( new ResourceEndpoint( $registrar, $name, $controller, $options ) )->setApi ( $this );
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
     *
     * @return \LaravelApi\Endpoints\Parameters\PathParameter
     */
    public function routeParameter ( $name )
    {
        return $this->parameters[ $name ] = new PathParameter ( compact ( 'name' ) );
    }


    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     * @throws \Exception
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

            return $this->getEndpointByUri ( $route->uri () )
                        ->getOperation ( $name, $route, $this->parameters );
        }

        return $this->macroCall ( $name, $arguments );
    }


    /**
     * @param string $uri
     *
     * @return Endpoints\Endpoint
     * @throws \Exception
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
     *
     * @return string
     */
    protected function cleanUpRouteUri ( $uri )
    {
        $basePath = trim ( $this->swagger->getBasePath (), '/' );
        $uri      = preg_replace ( "/^{$basePath}/", '', $uri );
        return '/' . trim ( $uri, '/' );
    }


    /**
     * @param string $uri
     * @param array  $resources
     *
     * @return Endpoints\Operation
     * @throws \Exception
     */
    public function aggregate ( $uri, array $resources )
    {
        $controller = AggregateController::class;

        $route = $this->router->get ( $uri, "\\{$controller}@index" )
                              ->defaults ( 'resources', $resources );

        return $this->getEndpointByUri ( $route->uri () )
                    ->getOperation ( 'get', $route );
    }


    /**
     * @param array|string $models
     */
    public function models ( $models )
    {
        $this->app->make ( Endpoints\ModelsEndpointRegistry::class )->add (
            is_array ( $models ) ? $models : func_get_args ()
        );
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
