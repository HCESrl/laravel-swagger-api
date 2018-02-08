<?php

namespace LaravelApi\Http\Controllers;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Routing\Controller as IlluminateController;
use LaravelApi\Endpoints\ModelsEndpointRegistry;


class ModelsController extends IlluminateController
{
	
	/**
	 * @var ModelsEndpointRegistry
	 */
	protected $registry;
	
	
	/**
	 * ModelsController constructor.
	 * @param ModelsEndpointRegistry $registry
	 */
	public function __construct ( ModelsEndpointRegistry $registry )
	{
		$this->registry = $registry;
	}
	
	
	/**
	 * @todo add support for 'all' parameter
	 * @param Model $model
	 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 * @throws AuthorizationException
	 */
	public function index ( Model $model )
	{
		$this->authorize ( $model );
		
		$builder = $model->newQuery ();
		
		$resource = $builder->paginate ();
		
		if ( method_exists ( $model, 'toApiResourceCollection' ) )
		{
			return $model->toApiResourceCollection ( $resource );
		}
		
		return Resource::collection ( $resource );
	}
	
	
	/**
	 * @param Model $model
	 * @param int   $id
	 * @return Resource
	 * @throws AuthorizationException
	 */
	public function show ( Model $model, $id )
	{
		$this->authorize ( $model );
		
		$resource = $model->findOrFail ( $id );
		
		if ( method_exists ( $model, 'toApiResource' ) )
		{
			return $model->toApiResource ( $resource );
		}
		
		return new Resource( $resource );
	}
	
	
	/**
	 * @param Model $model
	 * @throws AuthorizationException
	 */
	protected function authorize ( Model $model )
	{
		if ( ! $this->registry->has ( get_class ( $model ) ) )
		{
			throw new AuthorizationException( 'This action is unauthorized.' );
		}
	}
	
}