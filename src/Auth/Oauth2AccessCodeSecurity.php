<?php

namespace LaravelApi\Auth;


class Oauth2AccessCodeSecurity extends BasicAuthenticationSecurity
{

    use Concerns\HasAuthorizationUrl;
    use Concerns\HasTokenUrl;
    use Concerns\HasScopes;


    /**
     * @var \Calcinai\Strut\Definitions\Oauth2AccessCodeSecurity
     */
    protected $security;


    /**
     * Oauth2ImplicitSecurity constructor.
     */
    public function __construct()
    {
        $this->security = new \Calcinai\Strut\Definitions\Oauth2AccessCodeSecurity();
    }

}
