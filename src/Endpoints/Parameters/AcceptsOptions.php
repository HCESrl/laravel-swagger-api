<?php

namespace LaravelApi\Endpoints\Parameters;


trait AcceptsOptions
{
	
	
	/**
	 * @param string|array $options
	 * @return FormDataParameter|PathParameter
	 */
	public function addOptions ( $options )
	{
		$options = is_array ( $options ) ? $options : func_get_args ();
		
		foreach ( $options as $option )
		{
			$this->addEnum ( $option );
		}
		
		return $this;
	}
	
	
}