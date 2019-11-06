<?php

namespace LaravelApi\Tests;


use LaravelApi\Api;
use Orchestra\Testbench\TestCase as OrchestraTestCase;


class TestCase extends OrchestraTestCase
{

    /**
     * @var Api
     */
    protected $api;


    protected function setUp (): void
    {
        parent::setUp ();

        $this->api = $this->app->make ( Api::class );
    }


}
