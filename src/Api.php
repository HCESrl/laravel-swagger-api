<?php

namespace Finnegan\Api;


class Api
{
	
	
	protected $models    = [];
	
	protected $endpoints = [];
	
	
	public function models ( $models )
	{
		$models = is_array ( $models ) ? $models : func_get_args ();
		
		$this->models = array_unique ( array_merge ( $this->models, $models ) );
		
		return $this;
	}
	
	
	/**
	 * @param string          $path
	 * @param string|callable $use
	 * @param string          $method
	 * @return $this
	 */
	public function endpoint ( $path, $use, $method = 'get' )
	{
		$this->endpoints[ $path ] = compact ( 'use', 'method' );
		
		return $this;
	}
	
}