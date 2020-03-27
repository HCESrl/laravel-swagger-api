<?php

namespace LaravelApi\Endpoints;


use Calcinai\Strut\Definitions\BodyParameter;
use Calcinai\Strut\Definitions\HeaderParameterSubSchema;
use Calcinai\Strut\Definitions\Operation as StrutOperation;
use Calcinai\Strut\Definitions\QueryParameterSubSchema;
use Calcinai\Strut\Definitions\Response;
use Calcinai\Strut\Definitions\Responses;
use Calcinai\Strut\Definitions\SecurityRequirement;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationRuleParser;


/**
 * @method $this defaults(string $key, mixed $value)
 * @method $this name(string $name)
 * @method $this uses(\Closure | string $action)
 * @method $this setUri(string $uri)
 * @method $this prefix(string $prefix)
 * @method $this domain(string $domain = null)
 * @method $this where(array | string $name, string $expression = null)
 * @method $this middleware(array | string $middleware = null)
 * @method $this fallback()
 * @method string uri()
 * @method string getName()
 * @method mixed getAction(string | null $key = null)
 * @method string getActionName()
 * @method string getActionMethod()
 * @method string getPrefix()
 * @method string|null getDomain()
 */
class Operation extends StrutOperation
{

    /**
     * @var \Illuminate\Routing\Route
     */
    protected $route;

    /**
     * @var \Illuminate\Validation\ValidationRuleParser
     */
    protected $ruleParser;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->setResponses(Responses::create());
    }


    /**
     * @param \Illuminate\Routing\Route $route
     * @param array                     $parameters
     *
     * @return $this
     */
    public function setRoute(Route $route, array $parameters = [])
    {
        $this->route = $route;

        $this->initTags((array)$route->getAction('tags'));

        if ( ! $route->getAction('uses') instanceof \Closure) {
            $this->initOperationId($route);
        }

        if (config('api.parse_route_parameters')) {
            $this->parseRouteParameters($route->getDomain() . $route->uri(), $parameters);
        }

        return $this;
    }


    /**
     * @param array $tags
     *
     * @return $this
     */
    protected function initTags(array $tags)
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
        return $this;
    }


    /**
     * @param \Illuminate\Routing\Route $route
     *
     * @return $this
     */
    protected function initOperationId(Route $route)
    {
        $operationId = Str::camel($route->getActionMethod());

        return $this->setOperationId($operationId);
    }


    /**
     * @param string $uri
     * @param array  $parameters
     *
     * @return $this
     */
    public function parseRouteParameters($uri, array $parameters = [])
    {
        preg_match_all('/\{(.*?)\}/', $uri, $matches);

        array_map(
            function ($match) use ($parameters) {
                $required = ! Str::endsWith($match, '?');
                $name     = trim($match, '?');

                if (Arr::has($parameters, $match)) {
                    $parameter = clone $parameters[ $name ];
                    if ($required) {
                        $parameter->setRequired(true);
                    } elseif ($parameter->has('required')) {
                        $parameter->remove('required');
                    }
                    $this->addParameter($parameters[ $name ]);
                } else {
                    $this->addPathParameter($name, null, $required, 'string');
                }
            },
            $matches[ 1 ]
        );

        return $this;
    }


    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return \LaravelApi\Endpoints\Endpoint
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->route, $name)) {
            $result = call_user_func_array([$this->route, $name], $arguments);
            return ($result instanceof Route) ? $this : $result;
        }

        throw new \BadMethodCallException("Method {$name} does not exist.");
    }


    /**
     * @param string          $name
     * @param string|callable $descriptionOrCallback
     * @param bool            $required
     * @param string          $type
     *
     * @return $this
     */
    public function addHeaderParameter($name, $descriptionOrCallback = null, $required = false, $type = 'string')
    {
        return $this->registerParameter(
            HeaderParameterSubSchema::class,
            $name,
            $descriptionOrCallback,
            $required,
            $type
        );
    }


    /**
     * @param string          $name
     * @param string|callable $descriptionOrCallback
     * @param bool            $required
     * @param string          $type
     *
     * @return $this
     */
    public function addQueryParameter($name, $descriptionOrCallback = null, $required = false, $type = 'string')
    {
        return $this->registerParameter(
            QueryParameterSubSchema::class,
            $name,
            $descriptionOrCallback,
            $required,
            $type
        );
    }


    /**
     * @param string          $name
     * @param string|callable $descriptionOrCallback
     * @param bool            $required
     * @param string          $type
     *
     * @return $this
     */
    public function addPathParameter($name, $descriptionOrCallback = null, $required = false, $type = 'string')
    {
        return $this->registerParameter(
            Parameters\PathParameter::class,
            $name,
            $descriptionOrCallback,
            $required,
            $type
        );
    }


    /**
     * @param string          $name
     * @param string|callable $descriptionOrCallback
     * @param bool            $required
     * @param string          $type
     *
     * @return $this
     */
    public function addFormDataParameter($name, $descriptionOrCallback = null, $required = false, $type = 'string')
    {
        if ( ! $this->has('consumes')) {
            $this->setConsumes(['application/x-www-form-urlencoded']);
        }
        return $this->registerParameter(
            Parameters\FormDataParameter::class,
            $name,
            $descriptionOrCallback,
            $required,
            $type
        );
    }


    /**
     * @param string          $name
     * @param string|callable $descriptionOrCallback
     * @param bool            $required
     *
     * @return $this
     */
    public function addBodyParameter($name, $descriptionOrCallback = null, $required = false)
    {
        return $this->registerParameter(BodyParameter::class, $name, $descriptionOrCallback, $required);
    }


    /**
     * @param string          $parameterType
     * @param string          $name
     * @param string|callable $descriptionOrCallback
     * @param bool            $required
     * @param string          $type
     *
     * @return $this
     */
    protected function registerParameter(
        $parameterType,
        $name,
        $descriptionOrCallback = null,
        $required = false,
        $type = 'string'
    ) {
        $parameter = $this->getOrCreateParameter($parameterType, $name);

        if (method_exists($parameter, 'setType')) {
            $parameter->setType($type);
        }

        if ($required) {
            $parameter->setRequired($required);
        } elseif ($parameter->has('required')) {
            $parameter->remove('required');
        }

        if ($descriptionOrCallback instanceof \Closure) {
            $descriptionOrCallback($parameter);
        } elseif (is_string($descriptionOrCallback)) {
            $parameter->setDescription($descriptionOrCallback);
        }

        return $this;
    }


    /**
     * @param string $parameterType
     * @param string $name
     *
     * @return QueryParameterSubSchema|Parameters\PathParameter|Parameters\FormDataParameter|HeaderParameterSubSchema|BodyParameter
     */
    protected function getOrCreateParameter($parameterType, $name)
    {
        if ($this->has('parameters')) {
            if ($existingParameter = $this->retrieveParameter($name, $parameterType)) {
                return $existingParameter;
            }
        }

        $this->addParameter($parameter = new $parameterType(compact('name')));

        return $parameter;
    }


    /**
     * @param string $name
     * @param string $type
     *
     * @return QueryParameterSubSchema|Parameters\PathParameter|Parameters\FormDataParameter|HeaderParameterSubSchema|BodyParameter|null
     */
    protected function retrieveParameter($name, $type)
    {
        $parameters = Collection::make($this->getParameters());

        return $parameters->filter(
            function ($param) use ($name, $type) {
                return ($param instanceof $type and $param->getName() === $name);
            }
        )->first();
    }


    /**
     * @param integer $code
     * @param string  $description
     *
     * @return $this
     * @throws \Exception
     */
    public function addResponse($code, $description)
    {
        $this->getResponses()->set($code, Response::create(compact('description')));

        return $this;
    }


    /**
     * @param string $request
     *
     * @return $this
     */
    public function bindRequest($request)
    {
        $rules = (new $request)->rules();

        return $this->bindRules(
            $this->getValidationRuleParser()->explode($rules)->rules
        );
    }


    /**
     * @param array $requestRules
     *
     * @return $this
     */
    protected function bindRules(array $requestRules)
    {
        foreach ($requestRules as $name => $rules) {
            $this->addFormDataParameter(
                $name,
                function ($param) use ($rules) {
                    $param->applyRules($rules);
                },
                in_array('required', $rules)
            );
        }

        return $this;
    }


    /**
     * @return \Illuminate\Validation\ValidationRuleParser
     */
    protected function getValidationRuleParser()
    {
        if (is_null($this->ruleParser)) {
            $this->ruleParser = new ValidationRuleParser([]);
        }
        return $this->ruleParser;
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
        if ($this->has('security')) {
            $security = $this->getSecurity();
        } else {
            $this->addSecurity($security = SecurityRequirement::create());
        }

        $security->set($name, $scopes);

        return $this;
    }

}
