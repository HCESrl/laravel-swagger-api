<?php

namespace Finnegan\Api;


use Illuminate\Support\Fluent;


/**
 * @property string          $uri
 * @property string|callable $action
 * @property string|array    $methods
 * @property string          $description
 * @property array           $parameters
 * @property array           $examples
 * @method ApiEndpoint uri( string $uri )
 * @method ApiEndpoint action( string | callable $action )
 * @method ApiEndpoint methods( string | array $methods )
 * @method ApiEndpoint description( string $description )
 * @method ApiEndpoint parameters( array $parameters )
 * @method ApiEndpoint examples( array $examples )
 */
class ApiEndpoint extends Fluent
{

}