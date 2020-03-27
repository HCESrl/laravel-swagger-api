<?php

namespace LaravelApi\Auth;


class BasicAuthenticationSecurity
{

    /**
     * @var \Calcinai\Strut\Definitions\BasicAuthenticationSecurity
     */
    protected $security;


    /**
     * BasicAuthenticationSecurity constructor.
     */
    public function __construct()
    {
        $this->security = new \Calcinai\Strut\Definitions\BasicAuthenticationSecurity();
    }


    /**
     * @param string $description
     *
     * @return $this
     */
    final public function description($description)
    {
        $this->security->setDescription($description);
        return $this;
    }


    final public function toBase()
    {
        return $this->security;
    }

}
