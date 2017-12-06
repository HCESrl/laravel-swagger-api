<?php

namespace Finnegan\Api;


use Finnegan\Models\Model;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Routing\Controller as IlluminateController;


class Controller extends IlluminateController
{
	
	/**
	 * @var Api
	 */
	protected $api;
	
	
	public function __construct ( Api $api )
	{
		$this->api = $api;
	}
	
	
	/**
	 * @todo add support for 'all' parameter
	 */
	public function index ( Request $request, Model $model )
	{
		$this->authorize ( $model );
		
		$builder = $model->newQuery ();
		
		if ( $model->isSearchable () )
		{
			$builder->search ( $request );
		}
		
		if ( $model->isSortable () )
		{
			$builder->sort ( $request );
		}
		
		$resource = $builder->paginate ();
		if ( method_exists ( $model, '__toApiResourceCollection' ) )
		{
			return $model->__toApiResourceCollection ( $resource );
		}
		return Resource::collection ( $resource );
	}
	
	
	public function show ( Model $model, $id )
	{
		$this->authorize ( $model );
		
		$resource = $model->findOrFail ( $id );
		if ( method_exists ( $model, '__toApiResource' ) )
		{
			return $model->__toApiResource ( $resource );
		}
		return new Resource( $resource );
	}
	
	
	protected function authorize ( Model $model )
	{
		if ( ! $this->api->isWhitelisted ( get_class ( $model ) ) )
		{
			throw new AuthorizationException( 'This action is unauthorized.' );
		}
	}
	
}