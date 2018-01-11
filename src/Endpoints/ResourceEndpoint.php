<?php

namespace Finnegan\Api\Endpoints;


use Calcinai\Strut\Definitions\PathParameterSubSchema;
use Finnegan\Api\ApiServer;
use Illuminate\Routing\PendingResourceRegistration;


class ResourceEndpoint extends PendingResourceRegistration
{
	
	/**
	 * @var ApiServer
	 */
	protected $api;
	
	protected $resourceDefaults = [ 'index', 'show', 'store', 'update', 'destroy' ];
	
	
	/**
	 * @param ApiServer $api
	 * @return ResourceEndpoint
	 */
	public function setApi ( ApiServer $api )
	{
		$this->api = $api;
		return $this;
	}
	
	
	public function __destruct ()
	{
		$this->registerPaths ();
		parent::__destruct ();
	}
	
	
	protected function registerPaths ()
	{
		$uri = $this->registrar->getResourceUri ( $this->name );
		$base = $this->registrar->getResourceWildcard ( last ( explode ( '.', $this->name ) ) );
		
		foreach ( $this->getResourceMethods () as $method )
		{
			$this->{'register' . ucfirst ( $method ) . 'Path'}( $uri, $base );
		}
	}
	
	
	protected function registerIndexPath ( $uri )
	{
		$this->api->getEndpointByUri ( $uri )
				  ->setMethod ( 'get' )
				  ->setSummary ( 'Get the list of resources.' );
	}
	
	
	protected function registerShowPath ( $uri, $base )
	{
		$this->api->getEndpointByUri ( $uri . '/{' . $base . '}' )
				  ->setMethod ( 'get' )
				  ->setSummary ( 'Get the resource by ID.' )
				  ->addPathParameter ( 'id', function ( PathParameterSubSchema $param ) {
					  $param->setRequired ( true )
							->setDescription ( 'The resource ID.' );
				  } );
	}
	
	
	protected function registerStorePath ( $uri )
	{
		$this->api->getEndpointByUri ( $uri )
				  ->setMethod ( 'post' )
				  ->setSummary ( 'Create a new resource.' );
	}
	
	
	protected function registerUpdatePath ( $uri, $base )
	{
		$this->api->getEndpointByUri ( $uri . '/{' . $base . '}' )
				  ->setMethod ( 'put' )
				  ->setSummary ( 'Update the resource by ID.' )
				  ->addPathParameter ( 'id', function ( PathParameterSubSchema $param ) {
					  $param->setRequired ( true )
							->setDescription ( 'The resource ID.' );
				  } );
	}
	
	
	protected function registerDestroyPath ( $uri, $base )
	{
		$this->api->getEndpointByUri ( $uri . '/{' . $base . '}' )
				  ->setMethod ( 'delete' )
				  ->setSummary ( 'Delete a resource by ID.' )
				  ->addPathParameter ( 'id', function ( PathParameterSubSchema $param ) {
					  $param->setRequired ( true )
							->setDescription ( 'The resource ID.' );
				  } );
	}
	
	
	protected function getResourceMethods ()
	{
		if ( isset( $this->options[ 'only' ] ) )
		{
			return array_intersect ( $this->resourceDefaults, (array) $this->options[ 'only' ] );
		}
		
		if ( isset( $this->options[ 'except' ] ) )
		{
			return array_diff ( $this->resourceDefaults, (array) $this->options[ 'except' ] );
		}
		
		return $this->resourceDefaults;
	}
	
}