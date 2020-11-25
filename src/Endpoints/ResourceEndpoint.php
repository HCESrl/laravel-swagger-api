<?php

namespace LaravelApi\Endpoints;


use Calcinai\Strut\Definitions\SecurityRequirement;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use LaravelApi\Api;


class ResourceEndpoint extends PendingResourceRegistration
{

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var SecurityRequirement
     */
    protected $auth;

    /**
     * @var array
     */
    protected $resourceDefaults = ['index', 'show', 'store', 'update', 'destroy'];


    /**
     * @param Api $api
     *
     * @return ResourceEndpoint
     */
    public function setApi(Api $api)
    {
        $this->api = $api;
        return $this;
    }


    public function __destruct()
    {
        $this->registerPaths();
        parent::__destruct();
    }


    protected function registerPaths()
    {
        $uri  = $this->registrar->getResourceUri($this->name);
        $base = $this->registrar->getResourceWildcard(last(explode('.', $this->name)));

        $name = Str::studly(Arr::first(explode('/', $this->name)));

        foreach ($this->getResourceMethods() as $method) {
            $operation = $this->{'register' . ucfirst($method) . 'Path'}($uri, $base, $name);

            if ($this->auth) {
                call_user_func_array([$operation, 'requiresAuth'], $this->auth);
            }
        }
    }


    /**
     * @param string $uri
     * @param string $base
     * @param string $name
     *
     * @return \LaravelApi\Endpoints\Operation
     * @throws \Exception
     */
    protected function registerIndexPath($uri, $base, $name)
    {
        return $this->api->getEndpointByUri($uri)
                         ->setMethod('get')
                         ->setSummary('Get the list of resources.')
                         ->setOperationId('get' . Str::plural($name))
                         ->parseRouteParameters($uri);
    }


    /**
     * @param string $uri
     * @param string $base
     * @param string $name
     *
     * @return \LaravelApi\Endpoints\Operation
     * @throws \Exception
     */
    protected function registerShowPath($uri, $base, $name)
    {
        return $this->api->getEndpointByUri($uri . '/{' . $base . '}')
                         ->setMethod('get')
                         ->setSummary('Get the resource by ID.')
                         ->setOperationId('get' . Str::singular($name))
                         ->parseRouteParameters($uri)
                         ->addPathParameter('id', 'The resource ID.', true, 'integer');
    }


    /**
     * @param string $uri
     * @param string $base
     * @param string $name
     *
     * @return \LaravelApi\Endpoints\Operation
     * @throws \Exception
     */
    protected function registerStorePath($uri, $base, $name)
    {
        return $this->api->getEndpointByUri($uri)
                         ->setMethod('post')
                         ->setSummary('Create a new resource.')
                         ->setOperationId('create' . Str::singular($name))
                         ->parseRouteParameters($uri);
    }


    /**
     * @param string $uri
     * @param string $base
     * @param string $name
     *
     * @return \LaravelApi\Endpoints\Operation
     * @throws \Exception
     */
    protected function registerUpdatePath($uri, $base, $name)
    {
        return $this->api->getEndpointByUri($uri . '/{' . $base . '}')
                         ->setMethod('put')
                         ->setSummary('Update the resource by ID.')
                         ->setOperationId('update' . Str::singular($name))
                         ->parseRouteParameters($uri)
                         ->addPathParameter('id', 'The resource ID.', true, 'integer');
    }


    /**
     * @param string $uri
     * @param string $base
     * @param string $name
     *
     * @return \LaravelApi\Endpoints\Operation
     * @throws \Exception
     */
    protected function registerDestroyPath($uri, $base, $name)
    {
        return $this->api->getEndpointByUri($uri . '/{' . $base . '}')
                         ->setMethod('delete')
                         ->setSummary('Delete a resource by ID.')
                         ->setOperationId('delete' . Str::singular($name))
                         ->parseRouteParameters($uri)
                         ->addPathParameter('id', 'The resource ID.', true, 'integer');
    }


    protected function getResourceMethods()
    {
        if (isset($this->options[ 'only' ])) {
            return array_intersect($this->resourceDefaults, (array)$this->options[ 'only' ]);
        }

        if (isset($this->options[ 'except' ])) {
            return array_diff($this->resourceDefaults, (array)$this->options[ 'except' ]);
        }

        return $this->resourceDefaults;
    }


    /**
     * @param string $name
     * @param array  $scopes
     *
     * @return $this
     * @throws \Exception
     */
    public function requiresAuth($name, $scopes = [])
    {
        $this->auth = compact('name', 'scopes');

        return $this;
    }

}
