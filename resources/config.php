<?php

return [

    /**
     * The API url prefix.
     */
    'prefix'                 => 'api',

    /**
     * The API title.
     */
    'title'                  => 'Laravel API',

    /**
     * The API description.
     */
    'description'            => '',

    /**
     * The current API version.
     */
    'version'                => '1.0.0',

    /**
     * The UI docExpansion configuration (none, list, full)
     */
    'doc_expansion'           => 'list',

    /**
     * The HTTP scheme through which the API is served.
     * The HTTP scheme is used only for configuration purposes in Swagger UI. If missing, the it will be guessed from
     * the current request.
     */
    //'scheme'      => 'http',

    /**
     * Whether the route path parameters should be parsed automatically or not.
     */
    'parse_route_parameters' => true,

    /**
     * The API subpath to the Swagger json schema (false to disable).
     * Note: disabling the json schema will also disable the Swagger UI page
     */
    'swagger_json_path'      => 'docs/swagger.json',

    /**
     * The API subpath to the Swagger UI page (false to disable).
     */
    'swagger_ui_path'        => env ( 'APP_DEBUG', false ) ? 'docs' : false,

];
