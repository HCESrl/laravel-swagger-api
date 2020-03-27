<?php

namespace LaravelApi\Auth;


class Oauth2PasswordSecurity extends BasicAuthenticationSecurity
{

    use Concerns\HasTokenUrl;
    use Concerns\HasScopes;


    /**
     * @var \Calcinai\Strut\Definitions\Oauth2PasswordSecurity
     */
    protected $security;


    /**
     * Oauth2ImplicitSecurity constructor.
     */
    public function __construct()
    {
        $this->security = new \Calcinai\Strut\Definitions\Oauth2PasswordSecurity();
    }

}
