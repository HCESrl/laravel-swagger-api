<?php

namespace LaravelApi\Tests;


use Calcinai\Strut\Definitions\Schema;
use LaravelApi\Definition;


class ApiDefinitionsTest extends TestCase
{

	public function testCreation ()
	{
		$definition = $this->api->definition ( 'DefinitionName' );

		$this->assertInstanceOf ( Definition::class, $definition );
	}


	public function testProperties ()
	{
		$definition = $this->api->definition ( 'DefinitionName' );

		$this->assertInstanceOf (
			Definition::class,
			$definition->addProperty ( 'name', 'Property description', 42, 'integer' )
		);

		$property = $definition->getProperties ()->get ( 'name' );
		$this->assertInstanceOf ( Schema::class, $property );
		$this->assertEquals ( 'integer', $property->getType () );
		$this->assertEquals ( 'Property description', $property->getDescription () );
		$this->assertEquals ( 42, $property->getDefault () );

		$schema = $definition->toRef ();
		$this->assertInstanceOf ( Schema::class, $schema );
		$this->assertEquals ( '#/definitions/DefinitionName', $schema->getRef () );
	}

}
