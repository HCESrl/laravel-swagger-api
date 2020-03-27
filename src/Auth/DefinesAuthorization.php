<?php

namespace LaravelApi\Auth;


use Calcinai\Strut\Definitions\SecurityDefinitions;


trait DefinesAuthorization
{


    /**
     * @param string $name
     *
     * @return \LaravelApi\Auth\BasicAuthenticationSecurity
     */
    public function basicAuthSecurity($name = 'basic_auth')
    {
        return $this->securityDefinition($name, BasicAuthenticationSecurity::class);
    }


    /**
     * @param string $name
     *
     * @return \LaravelApi\Auth\ApiKeySecurity
     */
    public function apiKeySecurity($name = 'api_key')
    {
        return $this->securityDefinition($name, ApiKeySecurity::class);
    }


    /**
     * @param string $name
     *
     * @return \LaravelApi\Auth\Oauth2ImplicitSecurity
     */
    public function oauth2ImplicitSecurity($name = 'oauth2_implicit')
    {
        return $this->securityDefinition($name, Oauth2ImplicitSecurity::class);
    }


    /**
     * @param string $name
     *
     * @return \LaravelApi\Auth\Oauth2AccessCodeSecurity
     */
    public function oauth2AccessCodeSecurity($name = 'oauth2_access_code')
    {
        return $this->securityDefinition($name, Oauth2AccessCodeSecurity::class);
    }


    /**
     * @param string $name
     *
     * @return \LaravelApi\Auth\Oauth2ApplicationSecurity
     */
    public function oauth2ApplicationSecurity($name = 'oauth2_application')
    {
        return $this->securityDefinition($name, Oauth2ApplicationSecurity::class);
    }


    /**
     * @param string $name
     *
     * @return \LaravelApi\Auth\Oauth2PasswordSecurity
     */
    public function oauth2PasswordSecurity($name = 'oauth2_password')
    {
        return $this->securityDefinition($name, Oauth2PasswordSecurity::class);
    }


    /**
     * @param string $name
     * @param string $class
     *
     * @return mixed
     */
    protected function securityDefinition($name, $class)
    {
        $instance = new $class;

        $this->getSecurityDefinitions()->set($name, $instance->toBase());

        return $instance;
    }


    /**
     * @return \Calcinai\Strut\Definitions\SecurityDefinitions
     */
    protected function getSecurityDefinitions()
    {
        if ( ! $this->swagger->has('securityDefinitions')) {
            $this->swagger->setSecurityDefinitions(SecurityDefinitions::create());
        }

        return $this->swagger->getSecurityDefinitions();
    }

}
