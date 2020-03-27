<?php

namespace LaravelApi;


use Illuminate\Support\Facades\Facade as IlluminateFacade;


/**
 * @method static \Calcinai\Strut\Swagger swagger()
 * @method static \Illuminate\Routing\Router group( \Closure|string|array $value, \Closure|string $routes )
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
 * @method static \LaravelApi\Endpoints\Operation aggregate ( string $uri, array $resources )
 * @method static \Calcinai\Strut\Definitions\Tag tag ( string $name, string $description = null )
 * @method static void models ( array|string $models )
 * @method static void resources( array $resources )
 * @method static void version( string $version, \Closure|string $routes )
 * @method static void tags( array $tags )
 * @method static \LaravelApi\Auth\BasicAuthenticationSecurity basicAuthSecurity(string $as = 'basic_auth')
 * @method static \LaravelApi\Auth\ApiKeySecurity apiKeySecurity(string $as = 'api_key')
 * @method static \LaravelApi\Auth\Oauth2ImplicitSecurity oauth2ImplicitSecurity($as = 'oauth2_implicit')
 * @method static \LaravelApi\Auth\Oauth2AccessCodeSecurity oauth2AccessCodeSecurity($as = 'oauth2_access_code')
 * @method static \LaravelApi\Auth\Oauth2ApplicationSecurity oauth2ApplicationSecurity($as = 'oauth2_application')
 * @method static \LaravelApi\Auth\Oauth2PasswordSecurity oauth2PasswordSecurity($as = 'oauth2_password')
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
