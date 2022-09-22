<?php

namespace LaravelApi\Endpoints;

use Calcinai\Strut\Definitions\PathItem;
use Illuminate\Routing\Route;

class Endpoint extends PathItem
{
    /**
     * @param  string  $method
     * @param  \Illuminate\Routing\Route  $route
     * @param  array  $parameters
     * @return \Calcinai\Strut\Definitions\Operation
     */
    public function getOperation($method, Route $route, array $parameters = [])
    {
        return $this->setMethod($method)
                    ->setRoute($route, $parameters);
    }

    /**
     * @param  string  $method
     * @return \Calcinai\Strut\Definitions\Operation
     */
    public function setMethod($method)
    {
        if (! $this->has(strtolower($method))) {
            $this->{'set'.ucfirst($method)}( new Operation );
        }

        return $this->{'get'.ucfirst($method)}();
    }
}
