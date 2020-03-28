<?php

namespace LaravelApi\Tests;


use LaravelApi\Auth\ApiKeySecurity;
use LaravelApi\Auth\BasicAuthenticationSecurity;
use LaravelApi\Auth\Oauth2AccessCodeSecurity;
use LaravelApi\Auth\Oauth2ApplicationSecurity;
use LaravelApi\Auth\Oauth2ImplicitSecurity;
use LaravelApi\Auth\Oauth2PasswordSecurity;


class SecurityTest extends TestCase
{


    protected function assertSecurityDefinitionExists($name)
    {
        $this->assertTrue($this->api->swagger()->getSecurityDefinitions()->has('test_auth'));
    }


    protected function assertSecurityDefinitionInstanceOf($expected, $name)
    {
        $this->assertInstanceOf(
            $expected,
            $this->api->swagger()->getSecurityDefinitions()->get('test_auth')
        );
    }


    public function testBasicAuthSecurity()
    {
        $security = $this->api->basicAuthSecurity('test_auth')
                              ->description('Test description');

        $this->assertInstanceOf(BasicAuthenticationSecurity::class, $security);

        $this->assertInstanceOf(
            'Calcinai\\Strut\\Definitions\\BasicAuthenticationSecurity',
            $security->toBase()
        );

        $this->assertEquals('Test description', $security->toBase()->getDescription());

        $this->assertSecurityDefinitionExists('test_auth');
        $this->assertSecurityDefinitionInstanceOf(
            'Calcinai\\Strut\\Definitions\\BasicAuthenticationSecurity',
            'test_auth'
        );
    }


    public function testApiKeySecurity()
    {
        $security = $this->api->apiKeySecurity('test_auth')
                              ->parameterName('param_name');

        $this->assertInstanceOf(ApiKeySecurity::class, $security);

        $this->assertEquals('param_name', $security->toBase()->getName());
        $this->assertEquals('header', $security->toBase()->getIn());

        $this->assertEquals('query', $security->inQuery()->toBase()->getIn());

        $this->assertInstanceOf(
            'Calcinai\\Strut\\Definitions\\ApiKeySecurity',
            $security->toBase()
        );

        $this->assertSecurityDefinitionExists('test_auth');
        $this->assertSecurityDefinitionInstanceOf(
            'Calcinai\\Strut\\Definitions\\ApiKeySecurity',
            'test_auth'
        );
    }


    protected function getScopes()
    {
        return [
            'read'  => 'Read something',
            'write' => 'Write something',
        ];
    }


    public function testOauth2ImplicitSecurity()
    {
        $security = $this->api->oauth2ImplicitSecurity('test_auth')
                              ->setScopes($this->getScopes())
                              ->authorizationUrl('http://foo.bar');

        $this->assertInstanceOf(Oauth2ImplicitSecurity::class, $security);

        $this->assertTrue($security->toBase()->getScopes()->has('read'));
        $this->assertEquals('http://foo.bar', $security->toBase()->getAuthorizationUrl());

        $this->assertInstanceOf(
            'Calcinai\\Strut\\Definitions\\Oauth2ImplicitSecurity',
            $security->toBase()
        );

        $this->assertSecurityDefinitionExists('test_auth');
        $this->assertSecurityDefinitionInstanceOf(
            'Calcinai\\Strut\\Definitions\\Oauth2ImplicitSecurity',
            'test_auth'
        );
    }


    public function testOauth2AccessCodeSecurity()
    {
        $security = $this->api->oauth2AccessCodeSecurity('test_auth')
                              ->setScopes($this->getScopes())
                              ->authorizationUrl('http://foo.bar');

        $this->assertInstanceOf(Oauth2AccessCodeSecurity::class, $security);

        $this->assertTrue($security->toBase()->getScopes()->has('read'));
        $this->assertEquals('http://foo.bar', $security->toBase()->getAuthorizationUrl());
        $this->assertEquals('http://foo.bar', $security->tokenUrl('http://foo.bar')->toBase()->getTokenUrl());

        $this->assertInstanceOf(
            'Calcinai\\Strut\\Definitions\\Oauth2AccessCodeSecurity',
            $security->toBase()
        );

        $this->assertSecurityDefinitionExists('test_auth');
        $this->assertSecurityDefinitionInstanceOf(
            'Calcinai\\Strut\\Definitions\\Oauth2AccessCodeSecurity',
            'test_auth'
        );
    }


    public function testOauth2PasswordSecurity()
    {
        $security = $this->api->oauth2PasswordSecurity('test_auth')
                              ->setScopes($this->getScopes())
                              ->tokenUrl('http://foo.bar');

        $this->assertInstanceOf(Oauth2PasswordSecurity::class, $security);

        $this->assertTrue($security->toBase()->getScopes()->has('read'));
        $this->assertEquals('http://foo.bar', $security->toBase()->getTokenUrl());

        $this->assertInstanceOf(
            'Calcinai\\Strut\\Definitions\\Oauth2PasswordSecurity',
            $security->toBase()
        );

        $this->assertSecurityDefinitionExists('test_auth');
        $this->assertSecurityDefinitionInstanceOf(
            'Calcinai\\Strut\\Definitions\\Oauth2PasswordSecurity',
            'test_auth'
        );
    }


    public function testOauth2ApplicationSecurity()
    {
        $security = $this->api->oauth2ApplicationSecurity('test_auth')
                              ->setScopes($this->getScopes())
                              ->tokenUrl('http://foo.bar');

        $this->assertInstanceOf(Oauth2ApplicationSecurity::class, $security);

        $this->assertTrue($security->toBase()->getScopes()->has('read'));
        $this->assertEquals('http://foo.bar', $security->toBase()->getTokenUrl());

        $this->assertInstanceOf(
            'Calcinai\\Strut\\Definitions\\Oauth2ApplicationSecurity',
            $security->toBase()
        );

        $this->assertSecurityDefinitionExists('test_auth');
        $this->assertSecurityDefinitionInstanceOf(
            'Calcinai\\Strut\\Definitions\\Oauth2ApplicationSecurity',
            'test_auth'
        );
    }

}
