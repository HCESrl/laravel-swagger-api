<?php

namespace Finnegan\Api\Endpoints;


use Calcinai\Strut\Definitions\FormDataParameterSubSchema;
use Calcinai\Strut\Definitions\Operation as StrutOperation;
use Calcinai\Strut\Definitions\PathParameterSubSchema;
use Calcinai\Strut\Definitions\QueryParameterSubSchema;
use Calcinai\Strut\Definitions\Response;
use Calcinai\Strut\Definitions\Responses;
use Finnegan\Api\Definition;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;


class Operation extends StrutOperation
{
	
	public function __construct ( $data = [] )
	{
		parent::__construct ( $data );
		
		$this->setResponses ( Responses::create () );
	}
	
	
	/**
	 * @param string          $name
	 * @param string|callable $descriptionOrCallback
	 * @param string          $type
	 * @param bool            $required
	 * @return Operation
	 */
	public function addQueryParameter ( $name, $descriptionOrCallback = null, $type = 'string', $required = true )
	{
		return $this->registerParameter ( QueryParameterSubSchema::class, $name, $descriptionOrCallback, $type, $required );
	}
	
	
	/**
	 * @param string          $name
	 * @param string|callable $descriptionOrCallback
	 * @param string          $type
	 * @param bool            $required
	 * @return Operation
	 */
	public function addPathParameter ( $name, $descriptionOrCallback = null, $type = 'string', $required = true )
	{
		return $this->registerParameter ( PathParameterSubSchema::class, $name, $descriptionOrCallback, $type, $required );
	}
	
	
	/**
	 * @param string          $name
	 * @param string|callable $descriptionOrCallback
	 * @param string          $type
	 * @param bool            $required
	 * @return Operation
	 */
	public function addFormDataParameter ( $name, $descriptionOrCallback = null, $type = 'string', $required = true )
	{
		if ( ! $this->has ( 'consumes' ) )
		{
			$this->setConsumes ( [ 'application/x-www-form-urlencoded' ] );
		}
		return $this->registerParameter ( FormDataParameterSubSchema::class, $name, $descriptionOrCallback, $type, $required );
	}
	
	
	/**
	 * @param string          $parameterType
	 * @param string          $name
	 * @param string|callable $descriptionOrCallback
	 * @param string          $type
	 * @param bool            $required
	 * @return Operation
	 */
	protected function registerParameter ( $parameterType, $name, $descriptionOrCallback = null, $type = 'string', $required = true )
	{
		$parameter = new $parameterType( compact ( 'name', 'type', 'required' ) );
		
		if ( $descriptionOrCallback instanceof \Closure )
		{
			$descriptionOrCallback( $parameter );
		} elseif ( is_string ( $descriptionOrCallback ) )
		{
			$parameter->setDescription ( $descriptionOrCallback );
		}
		
		return $this->addParameter ( $parameter );
	}
	
	
	/**
	 * @param integer $code
	 * @param string  $description
	 * @return Operation
	 * @throws \Exception
	 */
	public function addResponse ( $code, $description )
	{
		$response = Response::create ( compact ( 'description' ) );
		
		$this->getResponses ()->set ( $code, $response );
		
		return $this;
	}
	
	
	/**
	 * @param array $tags
	 * @return Operation
	 */
	public function initTags ( array $tags )
	{
		foreach ( $tags as $tag )
		{
			$this->addTag ( $tag );
		}
		return $this;
	}
	
	
	/**
	 * @param Route $route
	 * @return Operation
	 */
	public function initOperationId ( Route $route )
	{
		$operationId = Str::camel ( $route->getActionMethod () );
		
		return $this->setOperationId ( $operationId );
	}
	
	
}