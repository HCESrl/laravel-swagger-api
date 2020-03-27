<?php

namespace LaravelApi\Auth;


class Oauth2ImplicitSecurity extends BasicAuthenticationSecurity
{

    use Concerns\HasAuthorizationUrl;
    use Concerns\HasScopes;


    /**
     * @var \Calcinai\Strut\Definitions\Oauth2ImplicitSecurity
     */
    protected $security;


    /**
     * Oauth2ImplicitSecurity constructor.
     */
    public function __construct()
    {
        $this->security = new \Calcinai\Strut\Definitions\Oauth2ImplicitSecurity();
    }

}
