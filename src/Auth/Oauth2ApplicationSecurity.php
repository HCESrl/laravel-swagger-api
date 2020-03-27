<?php

namespace LaravelApi\Auth;


class Oauth2ApplicationSecurity extends BasicAuthenticationSecurity
{

    use Concerns\HasTokenUrl;
    use Concerns\HasScopes;


    /**
     * @var \Calcinai\Strut\Definitions\Oauth2ApplicationSecurity
     */
    protected $security;


    /**
     * Oauth2ImplicitSecurity constructor.
     */
    public function __construct()
    {
        $this->security = new \Calcinai\Strut\Definitions\Oauth2ApplicationSecurity();
    }

}
