<?php

namespace LaravelApi;


use Calcinai\Strut\Definitions\Schema;
use Calcinai\Strut\Definitions\Schema\Properties\Properties;


class Definition extends Schema
{
	
	protected $name;
	
	
	public function __construct ( $data = [] )
	{
		parent::__construct ( $data );
		
		$this->setProperties ( Properties::create () );
	}
	
	
	/**
	 * @param string $name
	 * @return Definition
	 */
	public function setName ( $name )
	{
		$this->name = $name;
		return $this;
	}
	
	
	/**
	 * @param string $name
	 * @param string $type
	 * @param string $description
	 * @param string $default
	 * @return Definition
	 * @throws \Exception
	 */
	public function addProperty ( $name, $description = null, $default = null, $type = 'string' )
	{
		$property = Schema::create ( compact ( 'type', 'description', 'default' ) );
		
		$this->getProperties ()->set ( $name, $property );
		
		return $this;
	}
	
	
	/**
	 * @return Schema
	 */
	public function toRef ()
	{
		return Schema::create ()->setRef ( "#/definitions/{$this->name}" );
	}
	
}