
#### Customize the response
If you use custom [API Resources](https://laravel.com/docs/5.5/eloquent-resources) to personalize the API data you
can define the following methods on the very same model in order the provide the right resource to the API server.

```php
public function toApiResource ( $resource )
{
    return new MyCustomResource ( $resource );
}


public function toApiResourceCollection ( $resource )
{
    return new MyCustomResourceCollection ( $resource );
}
```