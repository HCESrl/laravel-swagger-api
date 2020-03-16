<?php

namespace LaravelApi;


use Illuminate\Support\Facades\Facade as IlluminateFacade;


/**
 * @method static \Illuminate\Routing\Router group( \Closure|string|array $value, \Closure|string $routes )
 * @method static void version( string $version, \Closure|string $routes )
 * @method static \LaravelApi\Endpoints\Operation get( string $uri, \Closure|array|string|null $action = null )
 * @method static \LaravelApi\Endpoints\Operation post( string $uri, \Closure|array|string|null $action = null )
 * @method static \LaravelApi\Endpoints\Operation put( string $uri, \Closure|array|string|null $action = null )
 * @method static \LaravelApi\Endpoints\Operation delete( string $uri, \Closure|array|string|null $action = null )
 * @method static \LaravelApi\Endpoints\Operation patch( string $uri, \Closure|array|string|null $action = null )
 * @method static \LaravelApi\Endpoints\Operation options( string $uri, \Closure|array|string|null $action = null )
 * @method static \LaravelApi\Endpoints\Operation any( string $uri, \Closure|array|string|null $action = null )
 * @method static \LaravelApi\Endpoints\Operation match( array|string $methods, string $uri, \Closure|array|string|null $action = null )
 * @method static \LaravelApi\Endpoints\Parameters\PathParameter routeParameter( string $name )
 * @method static \LaravelApi\Endpoints\ResourceEndpoint resource(string $name, string $controller, array $options = [])
 * @method static void resources( array $resources )
 * @method static \LaravelApi\Endpoints\Operation aggregate ( string $uri, array $resources )
 * @method static void models ( array|string $models )
 * @method static \Calcinai\Strut\Definitions\Tag tag ( string $name, string $description = null )
 * @method static void tags( array $tags )
 *
 * @see \LaravelApi\Api
 */
class Facade extends IlluminateFacade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor ()
    {
        return Api::class;
    }
}
