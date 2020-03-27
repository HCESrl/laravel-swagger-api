<?php


namespace LaravelApi\Auth\Concerns;


trait HasTokenUrl
{

    /**
     * @param string $url
     *
     * @return $this
     */
    public function tokenUrl($url)
    {
        $this->security->setTokenUrl($url);

        return $this;
    }

}
