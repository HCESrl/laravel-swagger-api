<?php

namespace LaravelApi\Endpoints\Parameters;


use Calcinai\Strut\Definitions\FormDataParameterSubSchema;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationRuleParser;


class FormDataParameter extends FormDataParameterSubSchema
{
	
	use AcceptsOptions;
	
	
	public function applyRules ( array $rules )
	{
		foreach ( $rules as $rule )
		{
			$rule = ValidationRuleParser::parse ( $rule );
			
			$method = "apply{$rule[0]}Rule";
			if ( method_exists ( $this, $method ) )
			{
				$this->$method( $rule[ 1 ] );
			}
		}
	}
	
	
	protected function applyBooleanRule ()
	{
		$this->setType ( 'boolean' );
	}
	
	
	protected function applyNumericRule ()
	{
		$this->setType ( 'number' );
	}
	
	
	protected function applyIntegerRule ()
	{
		$this->setType ( 'integer' );
	}
	
	
	protected function applyAcceptedRule ()
	{
		$this->setRequired ( true );
	}
	
	
	protected function applyMinRule ( array $parameters = [] )
	{
		$this->setMinimum ( Arr::get ( $parameters, 0 ) );
	}
	
	
	protected function applyMaxRule ( array $parameters = [] )
	{
		$this->setMaximum ( Arr::get ( $parameters, 0 ) );
	}
	
	
	protected function applyInRule ( array $parameters = [] )
	{
		$this->addOptions ( $parameters );
	}
	
}