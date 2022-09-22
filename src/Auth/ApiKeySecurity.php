<?php

namespace LaravelApi\Auth;

class ApiKeySecurity extends BasicAuthenticationSecurity
{
    /**
     * @var \Calcinai\Strut\Definitions\ApiKeySecurity
     */
    protected $security;

    /**
     * ApiKeySecurity constructor.
     */
    public function __construct()
    {
        $this->security = new \Calcinai\Strut\Definitions\ApiKeySecurity(
            [
                'in' => 'header',
                'name' => 'apiKey',
            ]
        );
    }

    /**
     * @param  string  $name
     * @return \LaravelApi\Auth\ApiKeySecurity
     */
    public function parameterName($name)
    {
        $this->security->setName($name);

        return $this;
    }

    /**
     * @return \LaravelApi\Auth\ApiKeySecurity
     */
    public function inHeader()
    {
        $this->security->setIn('header');

        return $this;
    }

    /**
     * @return \LaravelApi\Auth\ApiKeySecurity
     */
    public function inQuery()
    {
        $this->security->setIn('query');

        return $this;
    }
}
