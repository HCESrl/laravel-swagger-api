## finnegan/cds-api

[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

FinneganCDS is a web Content Design System built on top of [Laravel framework](http://laravel.com).

## Installation

Add the repository to your composer.json:
```json
{
    "repositories":
    [
        {
            "type":"vcs",
            "url":"git@gitlab.test5.hce.it:finnegan/extensions/cds-api.git"
        }
    ]
}
```

Install the package:
```bash
composer require finnegan/cds-api
```


## Configuration

You can configure the API in the `boot` method of your `AppServiceProvider`:
```php
public function boot ( Api $api )
{
    [...]
}
```

### Models RESTful API
The package has a built-in feature to expose your models through the API via a Resource Controller. To add a model to
the whitelist simply call the `model` method with the model class as argument:

```php
public function boot ( Api $api )
{
    $api->model ( 'App\\Page' );
}
```

### Custom endpoints
You can add a custom endpoint using the `endpoint` method.

The method takes 3 parameter:
*  the uri;
*  a controller action or a callback;
*  one or more http methods.

```php
public function boot ( Api $api )
{
    $api->endpoint ( 'foobar/uri', 'App\\ApiController@handlerFunction', 'post' );
}
```

The `endpoint` method returns an instance of ApiEndpoint that can be use to configure the endpoint metadata shown in
the API manifest

```php
public function boot ( Api $api )
{
    $api->endpoint ( 'foobar/uri', 'App\\ApiController@handlerFunction', 'post' )
        ->description ( 'The description of the endpoint' )
        ->arguments ( [
            [ 'foo', 'numeric', 'The description of the first argument.' ],
            [ 'bar', 'string', 'The description of the second argument.' ],
        ] )
        ->examples ( [
            [ 'The description of the example.', '/api/foobar/uri?foo=42' ],
        ] );
}
```

## The API Manifest
The package also contains a tool called API Manifest that shows an automatically generated API documentation. You can
access the manifest from the developer toolbar in the admin area.

## License

[FinneganCDS](https://gitlab.test5.hce.it/finnegan/extensions) and its extensions are open-sourced software licensed 
under the [MIT license](http://opensource.org/licenses/MIT).
