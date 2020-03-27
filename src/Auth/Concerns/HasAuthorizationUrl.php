<?php


namespace LaravelApi\Auth\Concerns;


trait HasAuthorizationUrl
{

    /**
     * @param string $url
     *
     * @return $this
     */
    public function authorizationUrl($url)
    {
        $this->security->setAuthorizationUrl($url);

        return $this;
    }

}
