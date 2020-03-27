<?php

namespace LaravelApi\Auth\Concerns;


use Calcinai\Strut\Definitions\Oauth2Scopes;


trait HasScopes
{

    public function setScopes(array $scopes)
    {
        $oauthScopes = Oauth2Scopes::create();
        foreach ($scopes as $name => $description) {
            $oauthScopes->add($name, $description);
        }
        $this->security->setScopes($oauthScopes);

        return $this;
    }


}
