<?php

namespace LaravelApi\Endpoints;


use Calcinai\Strut\Definitions\PathParameterSubSchema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LaravelApi\Api;
use LaravelApi\Http\Controllers\ModelsController;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;


class ModelsEndpointRegistry
{

	/**
	 * @var array
	 */
	protected $registry = [];

	/**
	 * @var Api
	 */
	protected $api;


	/**
	 * @var array|PathParameterSubSchema[]
	 */
	protected $parameters = [];


	/**
	 * @param Api $api
	 */
	public function __construct ( Api $api )
	{
		$this->api = $api;

		$api->resource ( 'models/{api_model}', '\\' . ModelsController::class )
			->only ( 'index', 'show' );

		$this->retrieveParameters ();
	}


	protected function retrieveParameters ()
	{
		$this->api->getEndpointByUri ( 'models/{api_model}' )
				  ->setMethod ( 'get' )
				  ->addTag ( 'models' )
				  ->addQueryParameter ( 'page', 'The page number.', false, 'integer' )
				  ->addPathParameter ( 'api_model', function ( $param ) {
					  $param->setDescription ( 'The model name.' );
					  $this->parameters[] = $param;
				  }, true );

		$this->api->getEndpointByUri ( 'models/{api_model}/{id}' )
				  ->setMethod ( 'get' )
				  ->addTag ( 'models' )
				  ->addPathParameter ( 'api_model', function ( $param ) {
					  $param->setDescription ( 'The model name.' );
					  $this->parameters[] = $param;
				  }, true );
	}


	/**
	 * @param array $models
	 * @return $this
	 */
	public function add ( array $models )
	{
		$options = [];

		foreach ( $models as $name => $model )
		{
			if ( is_numeric ( $name ) )
			{
				$name = $this->getModelName ( $model );
			}

			$options[] = $name;

			$this->registry[ $name ] = $model;
		}

		$this->addOptionsToParameters ( $options );

		return $this;
	}


	/**
	 * @param string $model
	 * @return string
	 */
	protected function getModelName ( $model )
	{
		return strtolower ( Str::plural ( Str::snake ( class_basename ( $model ) ) ) );
	}


	/**
	 * @param array $names
	 * @return $this
	 */
	protected function addOptionsToParameters ( array $names )
	{
		foreach ( $this->parameters as $parameter )
		{
			foreach ( $names as $name )
			{
				$parameter->addEnum ( $name );
			}
		}
		return $this;
	}


	/**
	 * @param string $name
	 * @return Model
	 */
	public function resolve ( $name )
	{
		if ( ! isset( $this->registry[ $name ] ) )
		{
			throw new ResourceNotFoundException( 'The model is not registered.' );
		}

		$model = $this->registry[ $name ];

		return new $model;
	}


	/**
	 * @param string $name
	 * @return bool
	 */
	public function has ( $name )
	{
		return in_array ( $name, $this->registry );
	}


	/**
	 * @return $this
	 */
	public function clear ()
	{
		$this->registry = [];
		return $this;
	}

}
