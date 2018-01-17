<?php

namespace LaravelApi\Endpoints;


use Calcinai\Strut\Definitions\PathParameterSubSchema;
use Illuminate\Routing\PendingResourceRegistration;
use LaravelApi\Api;


class ResourceEndpoint extends PendingResourceRegistration
{
	
	/**
	 * @var Api
	 */
	protected $api;
	
	protected $resourceDefaults = [ 'index', 'show', 'store', 'update', 'destroy' ];
	
	
	/**
	 * @param Api $api
	 * @return ResourceEndpoint
	 */
	public function setApi ( Api $api )
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
				  ->setSummary ( 'Get the list of resources.' )
				  ->parseRouteParameters ( $uri );
	}
	
	
	protected function registerShowPath ( $uri, $base )
	{
		$this->api->getEndpointByUri ( $uri . '/{' . $base . '}' )
				  ->setMethod ( 'get' )
				  ->setSummary ( 'Get the resource by ID.' )
				  ->parseRouteParameters ( $uri )
				  ->addPathParameter ( 'id', 'The resource ID.', true, 'integer' );
	}
	
	
	protected function registerStorePath ( $uri )
	{
		$this->api->getEndpointByUri ( $uri )
				  ->setMethod ( 'post' )
				  ->setSummary ( 'Create a new resource.' )
				  ->parseRouteParameters ( $uri );
	}
	
	
	protected function registerUpdatePath ( $uri, $base )
	{
		$this->api->getEndpointByUri ( $uri . '/{' . $base . '}' )
				  ->setMethod ( 'put' )
				  ->setSummary ( 'Update the resource by ID.' )
				  ->parseRouteParameters ( $uri )
				  ->addPathParameter ( 'id', 'The resource ID.', true, 'integer' );
	}
	
	
	protected function registerDestroyPath ( $uri, $base )
	{
		$this->api->getEndpointByUri ( $uri . '/{' . $base . '}' )
				  ->setMethod ( 'delete' )
				  ->setSummary ( 'Delete a resource by ID.' )
				  ->parseRouteParameters ( $uri )
				  ->addPathParameter ( 'id', 'The resource ID.', true, 'integer' );
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